<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GradeController extends Controller
{
    /**
     * Display a listing of all grades with student counts.
     */
    public function index(): Response
    {
        // Auto-seed grades if table is empty
        if (Grade::count() === 0) {
            $this->seedGrades();
        }

        $grades = Grade::withCount('students')
            ->with(['teachers' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email');
            }])
            ->orderBy('sort_order')
            ->get();

        // Count total students
        $totalStudents = Student::count();

        return Inertia::render('Portal/Admin/Grades/Index', [
            'grades' => $grades,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Seed default grade levels.
     */
    protected function seedGrades(): void
    {
        $grades = [
            ['name' => 'Ganeinu (Preschool)', 'sort_order' => 1],
            ['name' => 'Kindergarten', 'sort_order' => 2],
            ['name' => '1st Grade', 'sort_order' => 3],
            ['name' => '2nd Grade', 'sort_order' => 4],
            ['name' => '3rd Grade', 'sort_order' => 5],
            ['name' => '4th Grade', 'sort_order' => 6],
            ['name' => '5th Grade', 'sort_order' => 7],
            ['name' => '6th Grade', 'sort_order' => 8],
            ['name' => '7th Grade', 'sort_order' => 9],
            ['name' => '8th Grade', 'sort_order' => 10],
        ];

        foreach ($grades as $grade) {
            Grade::updateOrCreate(
                ['name' => $grade['name']],
                $grade
            );
        }
    }

    /**
     * Display the detail view for a specific grade.
     */
    public function show(Grade $grade): Response
    {
        $grade->load(['teachers' => function ($query) {
            $query->select('users.id', 'users.name', 'users.email');
        }]);
        
        // Get all staff members for potential assignment
        $allStaff = User::whereIn('role', [User::ROLE_TEACHER, User::ROLE_ADMIN])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        // Get students in this grade
        $students = Student::where('grade_id', $grade->id)
            ->with('parents:id,name,email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Portal/Admin/Grades/Show', [
            'grade' => $grade,
            'allStaff' => $allStaff,
            'students' => $students,
        ]);
    }

    /**
     * Update the teachers assigned to a grade.
     */
    public function updateTeachers(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        $grade->teachers()->sync($validated['teacher_ids'] ?? []);

        return redirect()->back()
            ->with('success', 'Grade teachers updated successfully.');
    }

    /**
     * Store a new student in this grade.
     */
    public function storeStudent(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Student::create([
            'name' => $validated['name'],
            'grade_id' => $grade->id,
        ]);

        return redirect()->back()
            ->with('success', 'Student added successfully.');
    }

    /**
     * Update a student.
     */
    public function updateStudent(Request $request, Grade $grade, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'nullable|exists:grades,id',
        ]);

        $student->update([
            'name' => $validated['name'],
            'grade_id' => $validated['grade_id'] ?? $grade->id,
        ]);

        return redirect()->back()
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete a student.
     */
    public function destroyStudent(Grade $grade, Student $student)
    {
        // Detach from all parents first
        $student->parents()->detach();
        
        $student->delete();

        return redirect()->back()
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Move students to a different grade.
     */
    public function moveStudents(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'target_grade_id' => 'required|exists:grades,id',
        ]);

        Student::whereIn('id', $validated['student_ids'])
            ->update(['grade_id' => $validated['target_grade_id']]);

        return redirect()->back()
            ->with('success', count($validated['student_ids']) . ' student(s) moved successfully.');
    }
}
