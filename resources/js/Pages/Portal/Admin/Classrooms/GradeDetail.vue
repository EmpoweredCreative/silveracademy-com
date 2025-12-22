<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed } from 'vue';
import { 
    AcademicCapIcon,
    UserGroupIcon,
    PlusIcon,
    PencilIcon,
    TrashIcon,
    ChevronLeftIcon,
    UserIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    grade: Object,
    teachers: Array,
    students: Array,
    unassignedStudents: Array,
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingClassroom = ref(null);

const createForm = useForm({
    grade_id: props.grade.id,
    name: '',
    teacher_id: null,
});

const editForm = useForm({
    name: '',
    teacher_id: null,
});

const openEditModal = (classroom) => {
    editingClassroom.value = classroom;
    editForm.name = classroom.name;
    editForm.teacher_id = classroom.teacher_id;
    showEditModal.value = true;
};

const submitCreate = () => {
    createForm.post('/portal/admin/classrooms', {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset('name', 'teacher_id');
        },
    });
};

const submitEdit = () => {
    editForm.put(`/portal/admin/classrooms/${editingClassroom.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
            editingClassroom.value = null;
        },
    });
};

const deleteClassroom = (classroom) => {
    if (classroom.students.length > 0) {
        alert('Cannot delete classroom with assigned students. Please reassign students first.');
        return;
    }
    if (confirm(`Are you sure you want to delete "${classroom.name}"?`)) {
        router.delete(`/portal/admin/classrooms/${classroom.id}`);
    }
};

const suggestedName = computed(() => {
    const existingCount = props.grade.classrooms?.length || 0;
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const letter = letters[existingCount] || (existingCount + 1);
    return `Room ${letter}`;
});

// Set suggested name when modal opens
const openCreateModal = () => {
    createForm.name = suggestedName.value;
    showCreateModal.value = true;
};
</script>

<template>
    <Head :title="`${grade.name} - Classrooms`" />

    <PortalLayout>
        <template #header>{{ grade.name }}</template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <div>
                <Link 
                    href="/portal/admin/classrooms"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to Grade Levels
                </Link>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600">
                        {{ grade.classrooms?.length || 0 }} classroom{{ grade.classrooms?.length !== 1 ? 's' : '' }}, 
                        {{ students.length }} student{{ students.length !== 1 ? 's' : '' }}
                    </p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Create Classroom
                </button>
            </div>

            <!-- Unassigned Students -->
            <div v-if="unassignedStudents.length > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <UserGroupIcon class="w-5 h-5 text-amber-700" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-amber-900">
                            {{ unassignedStudents.length }} student{{ unassignedStudents.length > 1 ? 's' : '' }} without classroom
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span 
                                v-for="student in unassignedStudents.slice(0, 5)" 
                                :key="student.id"
                                class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full"
                            >
                                {{ student.name }}
                            </span>
                            <span 
                                v-if="unassignedStudents.length > 5"
                                class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full"
                            >
                                +{{ unassignedStudents.length - 5 }} more
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classrooms Grid -->
            <div v-if="grade.classrooms?.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                    v-for="classroom in grade.classrooms" 
                    :key="classroom.id"
                    class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow"
                >
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-brand-50 rounded-lg">
                                    <AcademicCapIcon class="w-6 h-6 text-brand-600" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ classroom.name }}</h3>
                                    <p class="text-sm text-slate-500">
                                        {{ classroom.students?.length || 0 }} student{{ classroom.students?.length !== 1 ? 's' : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <button
                                    @click="openEditModal(classroom)"
                                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                                    title="Edit classroom"
                                >
                                    <PencilIcon class="w-4 h-4" />
                                </button>
                                <button
                                    @click="deleteClassroom(classroom)"
                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Delete classroom"
                                    :disabled="classroom.students?.length > 0"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Teacher -->
                        <div class="flex items-center gap-2 mb-4 p-3 bg-slate-50 rounded-lg">
                            <UserIcon class="w-4 h-4 text-slate-400" />
                            <span class="text-sm text-slate-700">
                                {{ classroom.teacher?.name || 'No teacher assigned' }}
                            </span>
                        </div>

                        <!-- Students Preview -->
                        <div v-if="classroom.students?.length > 0" class="space-y-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Students</p>
                            <div class="flex flex-wrap gap-1">
                                <span 
                                    v-for="student in classroom.students.slice(0, 4)" 
                                    :key="student.id"
                                    class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-700 text-xs rounded"
                                >
                                    {{ student.name }}
                                </span>
                                <span 
                                    v-if="classroom.students.length > 4"
                                    class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-500 text-xs rounded"
                                >
                                    +{{ classroom.students.length - 4 }} more
                                </span>
                            </div>
                        </div>
                        <p v-else class="text-sm text-slate-400 italic">No students assigned</p>

                        <!-- View Details Link -->
                        <Link 
                            :href="`/portal/admin/classrooms/${classroom.id}`"
                            class="mt-4 inline-flex items-center text-sm font-medium text-brand-600 hover:text-brand-700"
                        >
                            Manage Students →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <AcademicCapIcon class="mx-auto h-12 w-12 text-slate-400" />
                <h3 class="mt-2 text-sm font-medium text-slate-900">No classrooms yet</h3>
                <p class="mt-1 text-sm text-slate-500">Create your first classroom for {{ grade.name }}.</p>
                <div class="mt-6">
                    <button
                        @click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                    >
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Create Classroom
                    </button>
                </div>
            </div>
        </div>

        <!-- Create Classroom Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black/50" @click="showCreateModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Create Classroom</h3>
                        <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">
                            <XMarkIcon class="w-5 h-5" />
                        </button>
                    </div>

                    <form @submit.prevent="submitCreate" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Classroom Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                v-model="createForm.name"
                                type="text"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                placeholder="e.g., Room A or Mrs. Smith's Class"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Assign Teacher
                            </label>
                            <select 
                                v-model="createForm.teacher_id"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white"
                            >
                                <option :value="null">No teacher assigned</option>
                                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                    {{ teacher.name }} ({{ teacher.email }})
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button
                                type="button"
                                @click="showCreateModal = false"
                                class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="createForm.processing"
                                class="px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 transition-colors"
                            >
                                Create Classroom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Classroom Modal -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black/50" @click="showEditModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Edit Classroom</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                            <XMarkIcon class="w-5 h-5" />
                        </button>
                    </div>

                    <form @submit.prevent="submitEdit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Classroom Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                v-model="editForm.name"
                                type="text"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Assign Teacher
                            </label>
                            <select 
                                v-model="editForm.teacher_id"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white"
                            >
                                <option :value="null">No teacher assigned</option>
                                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                    {{ teacher.name }} ({{ teacher.email }})
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button
                                type="button"
                                @click="showEditModal = false"
                                class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="editForm.processing"
                                class="px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 transition-colors"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
