<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed } from 'vue';
import { 
    ChevronLeftIcon,
    UserIcon,
    EnvelopeIcon,
    CalendarIcon,
    CheckCircleIcon,
    XCircleIcon,
    UsersIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object,
    students: Array,
});

const form = useForm({
    children_ids: [],
});

const searchQuery = ref('');

const filteredStudents = computed(() => {
    if (!searchQuery.value) return props.students;
    const query = searchQuery.value.toLowerCase();
    return props.students.filter(s => 
        s.name.toLowerCase().includes(query) ||
        s.grade_name?.toLowerCase().includes(query)
    );
});

const toggleStudent = (studentId) => {
    const index = form.children_ids.indexOf(studentId);
    if (index > -1) {
        form.children_ids.splice(index, 1);
    } else {
        form.children_ids.push(studentId);
    }
};

const approve = () => {
    form.post(`/portal/admin/approvals/${props.user.id}/approve`);
};

const reject = () => {
    if (confirm('Are you sure you want to reject this registration? This will delete the user account.')) {
        router.post(`/portal/admin/approvals/${props.user.id}/reject`);
    }
};
</script>

<template>
    <Head :title="`Approve ${user.name}`" />

    <PortalLayout>
        <template #header>Review Registration</template>

        <div class="space-y-6">
            <!-- Back Link -->
            <div>
                <Link 
                    href="/portal/admin/approvals"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to Pending Approvals
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                            <h2 class="font-semibold text-slate-900">Registration Details</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-brand-100 flex items-center justify-center">
                                    <UserIcon class="w-8 h-8 text-brand-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ user.name }}</h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        Pending Approval
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-slate-200">
                                <div class="flex items-center gap-3 text-sm">
                                    <EnvelopeIcon class="w-5 h-5 text-slate-400" />
                                    <span class="text-slate-600">{{ user.email }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <CalendarIcon class="w-5 h-5 text-slate-400" />
                                    <span class="text-slate-600">Registered: {{ user.created_at_formatted }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-4 border-t border-slate-200 space-y-3">
                                <button
                                    @click="approve"
                                    :disabled="form.processing"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors"
                                >
                                    <CheckCircleIcon class="w-5 h-5" />
                                    {{ form.processing ? 'Approving...' : 'Approve & Send Credentials' }}
                                </button>
                                <button
                                    @click="reject"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-red-600 font-medium rounded-lg border border-red-300 hover:bg-red-50 transition-colors"
                                >
                                    <XCircleIcon class="w-5 h-5" />
                                    Reject Registration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Link Students Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                            <div class="flex items-center gap-3">
                                <UsersIcon class="w-5 h-5 text-slate-500" />
                                <div>
                                    <h2 class="font-semibold text-slate-900">Link Children (Optional)</h2>
                                    <p class="text-sm text-slate-500">Select students to link to this parent account</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border-b border-slate-200">
                            <div class="relative">
                                <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search students by name or grade..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                />
                            </div>
                        </div>

                        <div v-if="form.children_ids.length > 0" class="px-4 py-3 bg-brand-50 border-b border-brand-200">
                            <p class="text-sm text-brand-800">
                                <span class="font-medium">{{ form.children_ids.length }}</span> student(s) selected
                            </p>
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            <div v-if="filteredStudents.length === 0" class="px-6 py-8 text-center text-slate-500">
                                No students found.
                            </div>
                            <div v-else class="divide-y divide-slate-200">
                                <label
                                    v-for="student in filteredStudents"
                                    :key="student.id"
                                    class="flex items-center px-6 py-3 hover:bg-slate-50 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="form.children_ids.includes(student.id)"
                                        @change="toggleStudent(student.id)"
                                        class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                    />
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-slate-900">{{ student.name }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ student.grade_name || 'No Grade' }}
                                            <span v-if="student.classroom_name"> · {{ student.classroom_name }}</span>
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                            <p class="text-xs text-slate-500">
                                You can also link students later through the parent management interface.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
