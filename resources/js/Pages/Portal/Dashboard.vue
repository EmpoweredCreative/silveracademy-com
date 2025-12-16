<script setup>
import { Head } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';

defineProps({
    user: Object,
});

const stats = [
    { name: 'Unread Messages', value: '3', icon: '✉️' },
    { name: 'Upcoming Events', value: '5', icon: '📅' },
    { name: 'Documents', value: '12', icon: '📄' },
    { name: 'Announcements', value: '2', icon: '📢' },
];

const recentActivity = [
    { title: 'Math Assignment Graded', time: '2 hours ago', type: 'grade' },
    { title: 'New message from Mrs. Smith', time: '5 hours ago', type: 'message' },
    { title: 'Parent-Teacher Conference Reminder', time: '1 day ago', type: 'event' },
    { title: 'Report Card Available', time: '2 days ago', type: 'document' },
];
</script>

<template>
    <Head title="Dashboard" />

    <PortalLayout>
        <template #header>Welcome back, {{ user?.name || 'Parent' }}! 👋</template>
        
        <div class="space-y-8">
            <!-- Welcome Section -->
            <div>
                <p class="text-slate-600">
                    Here's what's happening with your child's education today.
                </p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="stat in stats"
                    :key="stat.name"
                    class="bg-white rounded-xl p-6 shadow-sm border border-slate-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="text-3xl">{{ stat.icon }}</div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ stat.value }}</p>
                            <p class="text-sm text-slate-600">{{ stat.name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Recent Activity</h2>
                    </div>
                    <div class="divide-y divide-slate-200">
                        <div
                            v-for="activity in recentActivity"
                            :key="activity.title"
                            class="px-6 py-4 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ activity.title }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ activity.time }}</p>
                                </div>
                                <span class="text-slate-400">→</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Quick Actions</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <button
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors"
                        >
                            <span class="text-3xl mb-2">✉️</span>
                            <span class="text-sm font-medium text-slate-700">Send Message</span>
                        </button>
                        <button
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors"
                        >
                            <span class="text-3xl mb-2">📅</span>
                            <span class="text-sm font-medium text-slate-700">View Calendar</span>
                        </button>
                        <button
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors"
                        >
                            <span class="text-3xl mb-2">📊</span>
                            <span class="text-sm font-medium text-slate-700">View Grades</span>
                        </button>
                        <button
                            class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-xl hover:bg-brand-50 transition-colors"
                        >
                            <span class="text-3xl mb-2">📄</span>
                            <span class="text-sm font-medium text-slate-700">Documents</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="bg-gradient-to-r from-brand-600 to-brand-700 rounded-xl p-6 text-white">
                <h2 class="text-lg font-semibold mb-2">📢 School Announcement</h2>
                <p class="text-brand-100">
                    Parent-Teacher conferences are scheduled for next week. Please log in to the calendar 
                    to book your preferred time slot.
                </p>
            </div>
        </div>
    </PortalLayout>
</template>


