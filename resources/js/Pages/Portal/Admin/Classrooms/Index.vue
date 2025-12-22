<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { 
    AcademicCapIcon,
    UserGroupIcon,
    BuildingOfficeIcon,
    ArrowUpTrayIcon,
    ArrowDownTrayIcon,
    ChevronRightIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    grades: Array,
    unassignedCount: Number,
});

const getTeacherNames = (classrooms) => {
    const teachers = classrooms
        .filter(c => c.teacher)
        .map(c => c.teacher.name);
    if (teachers.length === 0) return 'No teachers assigned';
    if (teachers.length <= 2) return teachers.join(', ');
    return teachers.slice(0, 2).join(', ') + ` +${teachers.length - 2} more`;
};
</script>

<template>
    <Head title="Classroom Management" />

    <PortalLayout>
        <template #header>Classroom Management</template>

        <div class="space-y-6">
            <!-- Header Actions -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600">Manage grades, classrooms, and student assignments.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a 
                        href="/portal/admin/classrooms/template"
                        class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors"
                    >
                        <ArrowDownTrayIcon class="w-4 h-4 mr-2" />
                        Download Template
                    </a>
                    <a 
                        href="/portal/admin/classrooms/export"
                        class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors"
                    >
                        <ArrowDownTrayIcon class="w-4 h-4 mr-2" />
                        Export Students
                    </a>
                    <Link
                        href="/portal/admin/classrooms/import"
                        class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                    >
                        <ArrowUpTrayIcon class="w-4 h-4 mr-2" />
                        Import Students
                    </Link>
                </div>
            </div>

            <!-- Unassigned Students Warning -->
            <div v-if="unassignedCount > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <UserGroupIcon class="w-5 h-5 text-amber-700" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-amber-900">
                            {{ unassignedCount }} student{{ unassignedCount > 1 ? 's' : '' }} without classroom assignment
                        </p>
                        <p class="text-sm text-amber-700">
                            Import a spreadsheet or manually assign students to classrooms.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grades List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-lg font-serif font-semibold text-slate-900">Grade Levels</h2>
                </div>

                <div v-if="grades.length === 0" class="p-12 text-center">
                    <AcademicCapIcon class="mx-auto h-12 w-12 text-slate-400" />
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No grades found</h3>
                    <p class="mt-1 text-sm text-slate-500">Run the grade seeder to populate grade levels.</p>
                </div>

                <div v-else class="divide-y divide-slate-200">
                    <Link
                        v-for="grade in grades"
                        :key="grade.id"
                        :href="`/portal/admin/classrooms/grade/${grade.id}`"
                        class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors group"
                    >
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-brand-50 rounded-lg group-hover:bg-brand-100 transition-colors">
                                <AcademicCapIcon class="w-6 h-6 text-brand-600" />
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ grade.name }}</h3>
                                <p class="text-sm text-slate-500">
                                    {{ getTeacherNames(grade.classrooms) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1 text-sm text-slate-600">
                                        <BuildingOfficeIcon class="w-4 h-4" />
                                        <span>{{ grade.classrooms_count }} classroom{{ grade.classrooms_count !== 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-sm text-slate-600">
                                        <UserGroupIcon class="w-4 h-4" />
                                        <span>{{ grade.students_count }} student{{ grade.students_count !== 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <ChevronRightIcon class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" />
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <AcademicCapIcon class="w-6 h-6 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ grades.length }}</p>
                            <p class="text-sm text-slate-600">Grade Levels</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <BuildingOfficeIcon class="w-6 h-6 text-emerald-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">
                                {{ grades.reduce((sum, g) => sum + g.classrooms_count, 0) }}
                            </p>
                            <p class="text-sm text-slate-600">Total Classrooms</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <UserGroupIcon class="w-6 h-6 text-purple-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">
                                {{ grades.reduce((sum, g) => sum + g.students_count, 0) }}
                            </p>
                            <p class="text-sm text-slate-600">Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
