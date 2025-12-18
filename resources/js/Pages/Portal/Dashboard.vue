<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { 
    CalendarIcon, 
    MegaphoneIcon, 
    EyeIcon,
    PlusIcon,
    ClipboardDocumentListIcon,
    UserGroupIcon,
    AcademicCapIcon,
    ClockIcon,
} from '@heroicons/vue/24/outline';
import { ref, computed } from 'vue';

const props = defineProps({
    user: Object,
    currentLunchMenu: Object,
    upcomingEvents: Array,
    recentAnnouncements: Array,
});

const previewRole = ref('admin'); // 'admin', 'teacher', 'parent'
const previewRoles = ['admin', 'teacher', 'parent'];

const isSuperAdmin = computed(() => props.user?.role === 'super_admin');
const isAdmin = computed(() => props.user?.role === 'admin' || props.user?.role === 'super_admin');

// Determine which view to show
const showAdminView = computed(() => {
    if (isSuperAdmin.value) {
        return previewRole.value === 'admin';
    }
    return isAdmin.value;
});

const showTeacherView = computed(() => {
    if (isSuperAdmin.value) {
        return previewRole.value === 'teacher';
    }
    return props.user?.role === 'teacher';
});

const showParentView = computed(() => {
    if (isSuperAdmin.value) {
        return previewRole.value === 'parent';
    }
    return props.user?.role === 'parent';
});

const toggleRole = () => {
    const currentIndex = previewRoles.indexOf(previewRole.value);
    const nextIndex = (currentIndex + 1) % previewRoles.length;
    previewRole.value = previewRoles[nextIndex];
};

const previewRoleLabel = computed(() => {
    const labels = {
        'admin': 'Admin View',
        'teacher': 'Teacher View',
        'parent': 'Parent View'
    };
    return labels[previewRole.value];
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { 
        weekday: 'short',
        month: 'short', 
        day: 'numeric'
    });
};

const formatWeekDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { 
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <PortalLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <span>Welcome back, {{ user?.name || 'User' }}!</span>
                
                <!-- Role Toggle for Super Admin -->
                <div v-if="isSuperAdmin" class="flex items-center gap-3">
                    <span class="text-sm text-slate-500 font-normal">Preview as:</span>
                    <button
                        @click="toggleRole"
                        class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm"
                    >
                        <EyeIcon class="w-4 h-4 mr-2 text-slate-500" />
                        {{ previewRoleLabel }}
                    </button>
                </div>
            </div>
        </template>
        
        <div class="space-y-8">
            <!-- ADMIN VIEW -->
            <template v-if="previewRole === 'admin'">
                <!-- Admin Stats Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-brand-50 rounded-lg">
                                <UserGroupIcon class="w-6 h-6 text-brand-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">142</p>
                                <p class="text-sm text-slate-600">Total Students</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-brand-50 rounded-lg">
                                <AcademicCapIcon class="w-6 h-6 text-brand-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">24</p>
                                <p class="text-sm text-slate-600">Staff Members</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-brand-50 rounded-lg">
                                <CalendarIcon class="w-6 h-6 text-brand-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ upcomingEvents?.length || 0 }}</p>
                                <p class="text-sm text-slate-600">Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-brand-50 rounded-lg">
                                <MegaphoneIcon class="w-6 h-6 text-brand-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ recentAnnouncements?.length || 0 }}</p>
                                <p class="text-sm text-slate-600">Recent Posts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-serif font-semibold text-slate-900">Admin Quick Actions</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            href="/portal/posts/create"
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors group"
                        >
                            <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-brand-100 transition-colors">
                                <MegaphoneIcon class="w-6 h-6 text-slate-600 group-hover:text-brand-600" />
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-brand-700 text-center">Post News/Event</span>
                        </Link>
                        <Link
                            href="/portal/lunch/create"
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors group"
                        >
                            <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-brand-100 transition-colors">
                                <ClipboardDocumentListIcon class="w-6 h-6 text-slate-600 group-hover:text-brand-600" />
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-brand-700 text-center">Add Lunch Menu</span>
                        </Link>
                        <Link
                            href="/portal/posts"
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors group"
                        >
                            <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-brand-100 transition-colors">
                                <PlusIcon class="w-6 h-6 text-slate-600 group-hover:text-brand-600" />
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-brand-700 text-center">Manage Posts</span>
                        </Link>
                        <Link
                            href="/portal/calendar"
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors group"
                        >
                            <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-brand-100 transition-colors">
                                <CalendarIcon class="w-6 h-6 text-slate-600 group-hover:text-brand-600" />
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-brand-700 text-center">View Calendar</span>
                        </Link>
                    </div>
                </div>
            </template>

            <!-- TEACHER VIEW -->
            <template v-else-if="previewRole === 'teacher'">
                <div class="space-y-6">
                    <!-- Teacher Welcome Card -->
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                                <AcademicCapIcon class="w-8 h-8 text-white" />
                            </div>
                            <div>
                                <h2 class="text-xl font-serif font-semibold">Teacher Dashboard</h2>
                                <p class="text-emerald-100 mt-1">Manage your classroom and communicate with parents</p>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h2 class="text-lg font-serif font-semibold text-slate-900">Quick Actions</h2>
                        </div>
                        <div class="p-6 grid grid-cols-2 lg:grid-cols-3 gap-4">
                            <Link
                                href="/portal/calendar"
                                class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors group"
                            >
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-emerald-100 transition-colors">
                                    <CalendarIcon class="w-6 h-6 text-slate-600 group-hover:text-emerald-600" />
                                </div>
                                <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-700 text-center">View Calendar</span>
                            </Link>
                            <Link
                                href="/portal/lunch"
                                class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors group"
                            >
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-emerald-100 transition-colors">
                                    <ClipboardDocumentListIcon class="w-6 h-6 text-slate-600 group-hover:text-emerald-600" />
                                </div>
                                <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-700 text-center">Lunch Menu</span>
                            </Link>
                            <Link
                                href="/news-events"
                                class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors group"
                            >
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:bg-emerald-100 transition-colors">
                                    <MegaphoneIcon class="w-6 h-6 text-slate-600 group-hover:text-emerald-600" />
                                </div>
                                <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-700 text-center">News & Events</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Upcoming Events for Teacher -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-serif font-semibold text-slate-900">Upcoming Events</h2>
                                <Link href="/portal/calendar" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                    View Calendar →
                                </Link>
                            </div>
                        </div>
                        <div v-if="upcomingEvents && upcomingEvents.length > 0" class="divide-y divide-slate-200">
                            <div
                                v-for="event in upcomingEvents"
                                :key="event.id"
                                class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 rounded-lg flex flex-col items-center justify-center">
                                        <span class="text-xs font-semibold text-emerald-600 uppercase">
                                            {{ new Date(event.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                        </span>
                                        <span class="text-lg font-bold text-emerald-700">
                                            {{ new Date(event.event_start_date).getDate() }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900">{{ event.title }}</p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center">
                                            <ClockIcon class="w-3 h-3 mr-1" />
                                            {{ formatDate(event.event_start_date) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-6 text-center">
                            <CalendarIcon class="mx-auto h-12 w-12 text-slate-300" />
                            <p class="mt-2 text-sm text-slate-500">No upcoming events.</p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- PARENT VIEW -->
            <template v-else-if="previewRole === 'parent'">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- This Week's Lunch Menu -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-amber-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-serif font-semibold text-amber-900">This Week's Lunch Menu</h2>
                                <Link href="/portal/lunch" class="text-sm text-amber-700 hover:text-amber-800 font-medium">
                                    View All →
                                </Link>
                            </div>
                            <p v-if="currentLunchMenu" class="text-sm text-amber-700 mt-1">
                                Week of {{ formatWeekDate(currentLunchMenu.week_start) }}
                            </p>
                        </div>
                        <div class="p-6">
                            <div v-if="currentLunchMenu" class="prose prose-sm max-w-none text-slate-700" v-html="currentLunchMenu.content"></div>
                            <div v-else class="text-center py-8">
                                <ClipboardDocumentListIcon class="mx-auto h-12 w-12 text-slate-300" />
                                <p class="mt-2 text-sm text-slate-500">No lunch menu posted for this week yet.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-serif font-semibold text-slate-900">Upcoming Events</h2>
                                <Link href="/portal/calendar" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                                    View Calendar →
                                </Link>
                            </div>
                        </div>
                        <div v-if="upcomingEvents && upcomingEvents.length > 0" class="divide-y divide-slate-200">
                            <div
                                v-for="event in upcomingEvents"
                                :key="event.id"
                                class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-lg flex flex-col items-center justify-center">
                                        <span class="text-xs font-semibold text-brand-600 uppercase">
                                            {{ new Date(event.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                        </span>
                                        <span class="text-lg font-bold text-brand-700">
                                            {{ new Date(event.event_start_date).getDate() }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900">{{ event.title }}</p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center">
                                            <ClockIcon class="w-3 h-3 mr-1" />
                                            {{ formatDate(event.event_start_date) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-6 text-center">
                            <CalendarIcon class="mx-auto h-12 w-12 text-slate-300" />
                            <p class="mt-2 text-sm text-slate-500">No upcoming events.</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Announcements -->
                <div v-if="recentAnnouncements && recentAnnouncements.length > 0" class="bg-gradient-to-r from-brand-600 to-brand-700 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                            <MegaphoneIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-serif font-semibold mb-2">Latest Announcement</h2>
                            <p class="text-brand-100 font-medium">{{ recentAnnouncements[0].title }}</p>
                            <p class="text-brand-200 text-sm mt-2 line-clamp-2">
                                {{ recentAnnouncements[0].content.replace(/<[^>]*>/g, '').substring(0, 200) }}...
                            </p>
                            <Link 
                                :href="`/news/${recentAnnouncements[0].slug}`" 
                                class="inline-block mt-4 text-sm font-medium text-white hover:text-brand-100"
                            >
                                Read more →
                            </Link>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </PortalLayout>
</template>
