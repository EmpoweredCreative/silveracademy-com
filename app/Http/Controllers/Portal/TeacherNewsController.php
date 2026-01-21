<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherNewsController extends Controller
{
    /**
     * Ensure only teachers and admins can access.
     */
    protected function authorizeStaffAccess(): void
    {
        $user = auth()->user();
        if (!$user->isTeacher() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only staff members can access this feature.');
        }
    }

    /**
     * Show the form for creating a new grade-specific news post.
     */
    public function create(): Response
    {
        $this->authorizeStaffAccess();
        
        $user = auth()->user();

        // Admins can post to any grade, teachers only to their assigned grades
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $grades = Grade::orderBy('name')->get(['id', 'name']);
        } else {
            $grades = $user->grades()->get(['grades.id', 'grades.name']);
        }

        return Inertia::render('Portal/TeacherNews/Create', [
            'grades' => $grades,
        ]);
    }

    /**
     * Store a new grade-specific news post.
     */
    public function store(Request $request)
    {
        $this->authorizeStaffAccess();
        
        $user = auth()->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_grade_id' => 'required|exists:grades,id',
        ]);

        // Admins can post to any grade, teachers only to their assigned grades
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            $teacherGradeIds = $user->grades()->pluck('grades.id')->toArray();
            if (!in_array($validated['target_grade_id'], $teacherGradeIds)) {
                return back()->withErrors(['target_grade_id' => 'You are not assigned to this grade.']);
            }
        }

        // Create the news post with grade-specific audience
        Post::create([
            'user_id' => $user->id,
            'type' => 'news',
            'audience' => 'grade', // This is for parents of this grade
            'target_grade_id' => $validated['target_grade_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_public' => false, // Portal only, not public website
            'published_at' => now(),
        ]);

        return redirect()->route('portal.dashboard')->with('success', 'News posted successfully to parents!');
    }

    /**
     * List teacher's posted news.
     */
    public function index(): Response
    {
        $this->authorizeStaffAccess();
        
        $user = auth()->user();

        $query = Post::with('targetGrade')
            ->where('type', 'news')
            ->where('audience', 'grade')
            ->orderBy('created_at', 'desc');

        // Admins see all grade news, teachers see only their own
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $posts = $query->get();

        return Inertia::render('Portal/TeacherNews/Index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Delete a teacher's news post.
     */
    public function destroy(Post $post)
    {
        $this->authorizeStaffAccess();
        
        $user = auth()->user();

        // Admins can delete any grade news, teachers can only delete their own
        if (!$user->isAdmin() && !$user->isSuperAdmin() && $post->user_id !== $user->id) {
            abort(403, 'You can only delete your own posts.');
        }

        $post->delete();

        return back()->with('success', 'News post deleted.');
    }
}
