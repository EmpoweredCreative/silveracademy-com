# Implementation Plan: Parent Emails, Import Template, and Code Emails

## Overview

- Add **up to 4 parent/contact emails per student** (not just one).
- Update the **grade management import template** with Parent Email 1–4 columns.
- Support **bulk email (per grade)** and **individual “Email code”** per student, with **max 5 codes per email** and multi-child instruction in all emails.

---

## 1. Data model: contact emails per student

- **New table:** `student_contact_emails`
  - `id`, `student_id` (FK), `email` (string), `created_at`, `updated_at`
  - Unique on `(student_id, email)` (normalized lowercase).
  - Enforce **max 4 rows per student** in application code (e.g. in import and any future UI).
- **No** single `parent_email` column on `students`; all contact emails live in this table.
- **Migration:** `create_student_contact_emails_table`.

**Resolving “who to send to”**

- **Bulk send:** For each student in the grade, get all rows from `student_contact_emails`. Each (email, student, code) is one recipient; then group by email (one email can receive multiple students’ codes).
- **Individual send:** For that student, get all contact emails from `student_contact_emails`; send one email to each address (same code). If none, fall back to first linked parent’s email if any.
- **Display “can send”:** Student has sendable targets if they have at least one contact email OR at least one linked parent.

---

## 2. Import template (grade management)

**File:** [app/Exports/StudentClassroomTemplateExport.php](app/Exports/StudentClassroomTemplateExport.php)

- In `StudentTemplateSheet`:
  - **Headings:** Add after “Teacher Email”: `Parent Email 1`, `Parent Email 2`, `Parent Email 3`, `Parent Email 4`.
  - **Sample rows:** Add 1–2 sample emails in first columns (e.g. `parent1@example.com`, `parent2@example.com`) and leave rest empty.
  - **Column widths:** Add columns E–H (e.g. 35 each for the four parent email columns).

---

## 3. Importer: read and save contact emails

**File:** [app/Imports/StudentClassroomImport.php](app/Imports/StudentClassroomImport.php)

- Read from row: `parent_email_1`, `parent_email_2`, `parent_email_3`, `parent_email_4` (and/or variants like `parent email 1`).
- Validate each non-empty value as email (optional field).
- After creating or updating the student:
  - Collect up to 4 valid, unique (normalized) emails.
  - Sync `student_contact_emails` for this student: delete existing, insert new rows (max 4).
- No change to code generation (still one code per student when created).

---

## 4. Storing plain code for email (encrypted)

- **Migration:** Add to `student_access_codes`: nullable `plain_code_encrypted` (text).
- **ParentCodeService** ([app/Services/ParentCodeService.php](app/Services/ParentCodeService.php)): In `createCodeForStudent`, after creating the access code, set `plain_code_encrypted` to `Crypt::encryptString($plainCode)` and save. When revoking old codes, no need to clear old encrypted value (new row gets the new one).
- **StudentAccessCode:** Add method used only when building email content, e.g. `getDecryptedPlainCode(): ?string` (returns `null` if column empty), using `Crypt::decryptString`. Do not expose in API or Inertia props.

---

## 5. Notifications and email copy

**New notification:** `App\Notifications\ParentCodeInvite`

- Accept: recipient email, optional name, list of `['student_name' => string, 'code' => string]`.
- Body: if one pair, “Your parent code for [name] is: [code]. Use it at [portal link] to sign up or link this child (Settings → Linked Students).” If multiple, list all “Child: CODE” and same instructions.
- **Include in every ParentCodeInvite:**  
  *“If you have more than one child at the school, you may receive separate emails with a code for each. After logging in, you can link each child in **Settings → Linked Students** by entering each code and clicking **Add child**.”*

**Update existing:** [app/Notifications/ParentCodeWelcome.php](app/Notifications/ParentCodeWelcome.php)

- Add (in both plain and HTML):  
  *“If you receive another Parent Code for another child, you can link them after logging in in **Settings → Linked Students** (enter the code and click Add child).”*

---

## 6. Bulk send (per grade, max 5 codes per email)

- **Route:** `POST /portal/admin/grades/{grade}/send-codes-to-parents` (e.g. `StudentCodeEmailController@bulkSendForGrade` or method on GradeController).
- **Logic:**
  - Load students for that grade that have at least one row in `student_contact_emails` and an active access code with stored encrypted plain code.
  - For each student, get all contact emails; for each email add `(email, student, decrypted_plain_code)`.
  - Group by normalized email.
  - For each group, take the list of (student_name, code); **chunk by 5** (max 5 codes per email). Send one `ParentCodeInvite` per chunk to that email.
- Redirect back to grade show with success flash (e.g. “Emails sent to N recipients”) or partial failure message.

---

## 7. Individual send (per student)

- **Route:** `POST /portal/admin/students/{student}/send-code-to-parent`.
- **Logic:** Resolve recipients: all emails from `student_contact_emails` for that student; if none, use first linked parent’s email if any. If still none, return 422 or redirect with error. Get active code’s decrypted plain code; send one `ParentCodeInvite` (single student/code) to **each** recipient.
- Redirect back (e.g. to grade show) with success/error flash.

---

## 8. Grade show UI

**File:** [resources/js/Pages/Portal/Admin/Grades/Show.vue](resources/js/Pages/Portal/Admin/Grades/Show.vue)

- **Bulk:** In the Students section header (same row as “Students” and “Add Student”), add button **“Email codes to parents”**. On click, optional confirm modal: “Send one email per parent address for this grade; parents with multiple children will receive one email with all their codes (up to 5 per email). Continue?” Then POST to bulk-send route. Handle loading and flash.
- **Per student:** In each student row, add an **“Email code”** control (e.g. envelope icon or “Email”) next to Regenerate. Show when the student has at least one contact email or at least one linked parent. Click: POST to individual-send route. If no contact emails and no linked parent, show disabled with tooltip “Add parent emails in import or link a parent.”

---

## 9. Exposing data to frontend

**GradeController show** ([app/Http/Controllers/Portal/Admin/GradeController.php](app/Http/Controllers/Portal/Admin/GradeController.php)):

- When loading students, eager-load `contactEmails` (new relationship on Student: `hasMany` or `belongsToMany` to a ContactEmail model, or a simple accessor that returns emails from `student_contact_emails`). Include in each student payload:
  - `contact_emails` (array of strings) and/or `has_contact_emails` (boolean), plus existing `parents` so the row can decide “can send” (contact emails or linked parents).

---

## 10. Model and relationship

- **Student:** Add relationship to contact emails (e.g. `contactEmails()` returning a relation for `student_contact_emails`). If you use a dedicated model `StudentContactEmail`, add that; otherwise use a custom query/accessor.
- **StudentContactEmail** (optional): Model with `student_id`, `email`, belonging to Student. Enforce max 4 per student in create/sync logic.

---

## 11. Implementation order (steps)

1. **Migrations:** Create `student_contact_emails`; add `plain_code_encrypted` to `student_access_codes`.
2. **Models:** Student relationship to contact emails; StudentAccessCode `getDecryptedPlainCode()`; fillable/guarded as needed.
3. **Template export:** Add Parent Email 1–4 columns and sample data in [StudentClassroomTemplateExport](app/Exports/StudentClassroomTemplateExport.php).
4. **Import:** In [StudentClassroomImport](app/Imports/StudentClassroomImport.php), read parent_email_1..4, validate, sync `student_contact_emails` (max 4 per student).
5. **ParentCodeService:** In [ParentCodeService](app/Services/ParentCodeService.php), after creating access code, set and save `plain_code_encrypted`.
6. **ParentCodeInvite:** Create notification with body and **multi-child / Settings instruction**.
7. **ParentCodeWelcome:** Add **multi-child / Settings instruction** to plain and HTML content.
8. **Bulk send:** Controller method + route; group by email, chunk by 5, send ParentCodeInvite.
9. **Individual send:** Controller method + route; send to all contact emails (or first linked parent if no contacts).
10. **GradeController show:** Load contact emails for students; add `contact_emails` / `has_contact_emails` to payload.
11. **Grade Show UI:** “Email codes to parents” button + confirm; per-row “Email code” button; show/hide based on contact emails or linked parents.

---

## 12. Email copy (exact wording to add)

**In ParentCodeInvite (new):**  
*“If you have more than one child at the school, you may receive separate emails with a code for each. After logging in, you can link each child in **Settings → Linked Students** by entering each code and clicking **Add child**.”*

**In ParentCodeWelcome (existing):**  
*“If you receive another Parent Code for another child, you can link them after logging in in **Settings → Linked Students** (enter the code and click Add child).”*
