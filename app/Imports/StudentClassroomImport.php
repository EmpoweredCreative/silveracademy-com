<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;

class StudentClassroomImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    protected array $errors = [];
    protected int $created = 0;
    protected int $updated = 0;
    protected int $skipped = 0;
    protected int $classroomsCreated = 0;
    
    // Cache for lookups to improve performance
    protected array $gradeCache = [];
    protected array $teacherCache = [];
    protected array $classroomCache = [];

    public function model(array $row)
    {
        // Normalize headers (handle different variations)
        $studentName = $row['student_name'] ?? $row['student'] ?? $row['name'] ?? null;
        $gradeName = $row['grade'] ?? $row['grade_level'] ?? null;
        $classroomName = $row['classroom_name'] ?? $row['classroom'] ?? $row['class'] ?? null;
        $teacherEmail = $row['teacher_email'] ?? $row['teacher'] ?? null;

        if (!$studentName || !$gradeName || !$classroomName) {
            $this->errors[] = "Missing required field(s) for student: " . ($studentName ?? 'Unknown');
            $this->skipped++;
            return null;
        }

        // Find or cache grade
        $grade = $this->findGrade($gradeName);
        if (!$grade) {
            $this->errors[] = "Grade '{$gradeName}' not found for student '{$studentName}'";
            $this->skipped++;
            return null;
        }

        // Find teacher if email provided
        $teacherId = null;
        if ($teacherEmail) {
            $teacher = $this->findTeacher($teacherEmail);
            if ($teacher) {
                $teacherId = $teacher->id;
            } else {
                // Log warning but continue - teacher might not exist yet
                $this->errors[] = "Warning: Teacher '{$teacherEmail}' not found for classroom '{$classroomName}'. Classroom created without teacher.";
            }
        }

        // Find or create classroom
        $classroom = $this->findOrCreateClassroom($grade, $classroomName, $teacherId);

        // Find or create student
        $student = Student::where('name', $studentName)
            ->where('grade_id', $grade->id)
            ->first();

        if ($student) {
            // Update existing student's classroom
            $student->update(['classroom_id' => $classroom->id]);
            $this->updated++;
        } else {
            // Create new student
            $student = Student::create([
                'name' => $studentName,
                'grade_id' => $grade->id,
                'classroom_id' => $classroom->id,
            ]);
            $this->created++;
        }

        return $student;
    }

    protected function findGrade(string $name): ?Grade
    {
        $name = trim($name);
        
        if (isset($this->gradeCache[$name])) {
            return $this->gradeCache[$name];
        }

        // Try exact match first
        $grade = Grade::where('name', $name)->first();
        
        // Try partial/fuzzy match if exact not found
        if (!$grade) {
            $grade = Grade::where('name', 'LIKE', "%{$name}%")->first();
        }

        $this->gradeCache[$name] = $grade;
        return $grade;
    }

    protected function findTeacher(string $email): ?User
    {
        $email = trim(strtolower($email));
        
        if (isset($this->teacherCache[$email])) {
            return $this->teacherCache[$email];
        }

        $teacher = User::where('email', $email)
            ->where('role', User::ROLE_TEACHER)
            ->first();
        
        // Also check if admin (admins can be assigned as teachers)
        if (!$teacher) {
            $teacher = User::where('email', $email)
                ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
                ->first();
        }

        $this->teacherCache[$email] = $teacher;
        return $teacher;
    }

    protected function findOrCreateClassroom(Grade $grade, string $name, ?int $teacherId): Classroom
    {
        $cacheKey = $grade->id . ':' . $name;
        
        if (isset($this->classroomCache[$cacheKey])) {
            $classroom = $this->classroomCache[$cacheKey];
            
            // Update teacher if provided and classroom doesn't have one
            if ($teacherId && !$classroom->teacher_id) {
                $classroom->update(['teacher_id' => $teacherId]);
            }
            
            return $classroom;
        }

        $classroom = Classroom::where('grade_id', $grade->id)
            ->where('name', $name)
            ->first();

        if (!$classroom) {
            $classroom = Classroom::create([
                'grade_id' => $grade->id,
                'name' => $name,
                'teacher_id' => $teacherId,
            ]);
            $this->classroomsCreated++;
        } elseif ($teacherId && !$classroom->teacher_id) {
            // Update teacher if not set
            $classroom->update(['teacher_id' => $teacherId]);
        }

        $this->classroomCache[$cacheKey] = $classroom;
        return $classroom;
    }

    public function rules(): array
    {
        return [
            'student_name' => 'nullable|string|max:255',
            'grade' => 'nullable|string',
            'classroom_name' => 'nullable|string|max:255',
            'teacher_email' => 'nullable|email',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'teacher_email.email' => 'The teacher email must be a valid email address.',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            $this->skipped++;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getStats(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'skipped' => $this->skipped,
            'classrooms_created' => $this->classroomsCreated,
        ];
    }
}
