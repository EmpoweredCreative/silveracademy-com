<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClassroomController extends Controller
{
    /**
     * Display a listing of all grades with classroom counts.
     */
    public function index(): Response
    {
        $grades = Grade::withCount(['classrooms', 'students'])
            ->with(['classrooms.teacher'])
            ->orderBy('sort_order')
            ->get();

        // Count unassigned students (students without a classroom)
        $unassignedCount = Student::whereNull('classroom_id')->count();

        return Inertia::render('Portal/Admin/Classrooms/Index', [
            'grades' => $grades,
            'unassignedCount' => $unassignedCount,
        ]);
    }

    /**
     * Display the detail view for a specific grade.
     */
    public function gradeDetail(Grade $grade): Response
    {
        $grade->load(['classrooms.teacher', 'classrooms.students']);
        
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Get students in this grade grouped by classroom
        $students = Student::where('grade_id', $grade->id)
            ->with('classroom')
            ->orderBy('name')
            ->get();

        // Students without a classroom assignment
        $unassignedStudents = $students->whereNull('classroom_id');

        return Inertia::render('Portal/Admin/Classrooms/GradeDetail', [
            'grade' => $grade,
            'teachers' => $teachers,
            'students' => $students,
            'unassignedStudents' => $unassignedStudents->values(),
        ]);
    }

    /**
     * Display the detail view for a specific classroom.
     */
    public function show(Classroom $classroom): Response
    {
        $classroom->load(['grade', 'teacher', 'students']);
        
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Get other students in the same grade (for reassignment)
        $otherStudents = Student::where('grade_id', $classroom->grade_id)
            ->where(function ($query) use ($classroom) {
                $query->where('classroom_id', '!=', $classroom->id)
                    ->orWhereNull('classroom_id');
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('Portal/Admin/Classrooms/Show', [
            'classroom' => $classroom,
            'teachers' => $teachers,
            'otherStudents' => $otherStudents,
        ]);
    }

    /**
     * Store a newly created classroom.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classroom = Classroom::create($validated);

        return redirect()->route('admin.classrooms.grade.show', $classroom->grade_id)
            ->with('success', 'Classroom created successfully.');
    }

    /**
     * Update the specified classroom.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classroom->update($validated);

        return redirect()->back()
            ->with('success', 'Classroom updated successfully.');
    }

    /**
     * Remove the specified classroom.
     */
    public function destroy(Classroom $classroom)
    {
        // Check if there are students in this classroom
        if ($classroom->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete classroom with assigned students. Please reassign students first.');
        }

        $gradeId = $classroom->grade_id;
        $classroom->delete();

        return redirect()->route('admin.classrooms.grade.show', $gradeId)
            ->with('success', 'Classroom deleted successfully.');
    }

    /**
     * Assign students to a classroom.
     */
    public function assignStudents(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        Student::whereIn('id', $validated['student_ids'])
            ->update(['classroom_id' => $classroom->id]);

        return redirect()->back()
            ->with('success', count($validated['student_ids']) . ' student(s) assigned successfully.');
    }

    /**
     * Assign all students in a grade to a classroom.
     */
    public function assignAllStudents(Request $request, Classroom $classroom)
    {
        $count = Student::where('grade_id', $classroom->grade_id)
            ->update(['classroom_id' => $classroom->id]);

        return redirect()->back()
            ->with('success', "{$count} student(s) assigned to this classroom.");
    }

    /**
     * Remove students from a classroom (set classroom_id to null).
     */
    public function removeStudents(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        Student::whereIn('id', $validated['student_ids'])
            ->update(['classroom_id' => null]);

        return redirect()->back()
            ->with('success', count($validated['student_ids']) . ' student(s) removed from classroom.');
    }

    /**
     * Move students to a different classroom.
     */
    public function moveStudents(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'target_classroom_id' => 'required|exists:classrooms,id',
        ]);

        Student::whereIn('id', $validated['student_ids'])
            ->update(['classroom_id' => $validated['target_classroom_id']]);

        return redirect()->back()
            ->with('success', count($validated['student_ids']) . ' student(s) moved successfully.');
    }
}
