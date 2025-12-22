<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LunchMenu;
use App\Models\Post;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        // Get this week's lunch menus (Monday-Friday)
        $monday = Carbon::now()->startOfWeek();
        $friday = Carbon::now()->startOfWeek()->addDays(4);
        
        $thisWeekLunchMenus = LunchMenu::whereBetween('menu_date', [$monday, $friday])
            ->orderBy('menu_date', 'asc')
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'menu_date' => $menu->menu_date->format('Y-m-d'),
                    'content' => $menu->content,
                    'day_name' => $menu->day_name,
                    'short_day_name' => $menu->short_day_name,
                    'formatted_date' => $menu->formatted_date,
                ];
            });

        // Get upcoming events (from Posts) - public events only
        $upcomingEvents = Post::where('type', 'event')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('event_start_date', '>=', now())
            ->publicAudience()
            ->orderBy('event_start_date', 'asc')
            ->take(5)
            ->get();

        // Get latest announcements (from Posts) - public only for parents
        $recentAnnouncements = Post::where('type', 'news')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->publicAudience()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Get staff announcements (for teachers and admins)
        $teacherAnnouncements = collect();
        if ($user->isTeacher() || $user->isAdmin() || $user->isSuperAdmin()) {
            // Get the teacher's assigned grade (if any) from their classrooms
            $teacherGradeIds = [];
            if ($user->isTeacher()) {
                $teacherGradeIds = $user->classrooms()->pluck('grade_id')->unique()->toArray();
            }

            $teacherAnnouncements = Post::with(['targetGrade', 'targetTeacher'])
                ->where('type', 'news')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where(function ($query) use ($user, $teacherGradeIds) {
                    // All staff announcements
                    $query->where('audience', 'teachers_only');
                    
                    // Grade-specific announcements (for admins: all, for teachers: only their grades)
                    if ($user->isAdmin() || $user->isSuperAdmin()) {
                        $query->orWhere('audience', 'grade_teachers');
                    } elseif (!empty($teacherGradeIds)) {
                        $query->orWhere(function ($q) use ($teacherGradeIds) {
                            $q->where('audience', 'grade_teachers')
                                ->whereIn('target_grade_id', $teacherGradeIds);
                        });
                    }
                    
                    // Teacher-specific announcements (for admins: all, for teachers: only theirs)
                    if ($user->isAdmin() || $user->isSuperAdmin()) {
                        $query->orWhere('audience', 'specific_teacher');
                    } else {
                        $query->orWhere(function ($q) use ($user) {
                            $q->where('audience', 'specific_teacher')
                                ->where('target_teacher_id', $user->id);
                        });
                    }
                })
                ->orderBy('published_at', 'desc')
                ->take(10)
                ->get();
        }

        return Inertia::render('Portal/Dashboard', [
            'user' => $user,
            'thisWeekLunchMenus' => $thisWeekLunchMenus,
            'upcomingEvents' => $upcomingEvents,
            'recentAnnouncements' => $recentAnnouncements,
            'teacherAnnouncements' => $teacherAnnouncements,
        ]);
    }
}
