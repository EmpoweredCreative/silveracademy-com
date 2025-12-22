<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed } from 'vue';
import { 
    UserGroupIcon,
    PlusIcon,
    TrashIcon,
    ChevronLeftIcon,
    UserIcon,
    ArrowsRightLeftIcon,
    CheckIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    classroom: Object,
    teachers: Array,
    otherStudents: Array, // Students in the same grade but different or no classroom
});

const selectedStudents = ref([]);
const selectedToAdd = ref([]);
const showAddStudents = ref(false);

const toggleStudent = (studentId) => {
    const index = selectedStudents.value.indexOf(studentId);
    if (index === -1) {
        selectedStudents.value.push(studentId);
    } else {
        selectedStudents.value.splice(index, 1);
    }
};

const toggleAddStudent = (studentId) => {
    const index = selectedToAdd.value.indexOf(studentId);
    if (index === -1) {
        selectedToAdd.value.push(studentId);
    } else {
        selectedToAdd.value.splice(index, 1);
    }
};

const isSelected = (studentId) => selectedStudents.value.includes(studentId);
const isSelectedToAdd = (studentId) => selectedToAdd.value.includes(studentId);

const removeSelectedStudents = () => {
    if (selectedStudents.value.length === 0) return;
    if (confirm(`Remove ${selectedStudents.value.length} student(s) from this classroom?`)) {
        router.post(`/portal/admin/classrooms/${props.classroom.id}/remove-students`, {
            student_ids: selectedStudents.value,
        }, {
            onSuccess: () => {
                selectedStudents.value = [];
            },
        });
    }
};

const addSelectedStudents = () => {
    if (selectedToAdd.value.length === 0) return;
    router.post(`/portal/admin/classrooms/${props.classroom.id}/assign-students`, {
        student_ids: selectedToAdd.value,
    }, {
        onSuccess: () => {
            selectedToAdd.value = [];
            showAddStudents.value = false;
        },
    });
};

const selectAllStudents = () => {
    if (selectedStudents.value.length === props.classroom.students.length) {
        selectedStudents.value = [];
    } else {
        selectedStudents.value = props.classroom.students.map(s => s.id);
    }
};

const selectAllToAdd = () => {
    if (selectedToAdd.value.length === props.otherStudents.length) {
        selectedToAdd.value = [];
    } else {
        selectedToAdd.value = props.otherStudents.map(s => s.id);
    }
};

// Teacher update form
const teacherForm = useForm({
    name: props.classroom.name,
    teacher_id: props.classroom.teacher_id,
});

const updateTeacher = () => {
    teacherForm.put(`/portal/admin/classrooms/${props.classroom.id}`);
};

const allSelected = computed(() => 
    props.classroom.students?.length > 0 && 
    selectedStudents.value.length === props.classroom.students.length
);

const allToAddSelected = computed(() => 
    props.otherStudents?.length > 0 && 
    selectedToAdd.value.length === props.otherStudents.length
);
</script>

<template>
    <Head :title="`${classroom.name} - ${classroom.grade?.name}`" />

    <PortalLayout>
        <template #header>{{ classroom.name }}</template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <div>
                <Link 
                    :href="`/portal/admin/classrooms/grade/${classroom.grade_id}`"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to {{ classroom.grade?.name }}
                </Link>
            </div>

            <!-- Classroom Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-500">{{ classroom.grade?.name }}</p>
                        <h2 class="text-xl font-serif font-semibold text-slate-900">{{ classroom.name }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-slate-900">{{ classroom.students?.length || 0 }}</p>
                        <p class="text-sm text-slate-500">Students</p>
                    </div>
                </div>

                <!-- Teacher Assignment -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Assigned Teacher
                    </label>
                    <div class="flex items-center gap-3">
                        <select 
                            v-model="teacherForm.teacher_id"
                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white"
                        >
                            <option :value="null">No teacher assigned</option>
                            <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                {{ teacher.name }} ({{ teacher.email }})
                            </option>
                        </select>
                        <button
                            @click="updateTeacher"
                            :disabled="teacherForm.processing || teacherForm.teacher_id === classroom.teacher_id"
                            class="px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50 transition-colors"
                        >
                            Update
                        </button>
                    </div>
                </div>
            </div>

            <!-- Students Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-serif font-semibold text-slate-900">Students</h2>
                        <span class="text-sm text-slate-500">
                            {{ classroom.students?.length || 0 }} enrolled
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            v-if="selectedStudents.length > 0"
                            @click="removeSelectedStudents"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                        >
                            <TrashIcon class="w-4 h-4 mr-1" />
                            Remove ({{ selectedStudents.length }})
                        </button>
                        <button
                            @click="showAddStudents = !showAddStudents"
                            class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 transition-colors"
                        >
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Add Students
                        </button>
                    </div>
                </div>

                <!-- Add Students Panel -->
                <div v-if="showAddStudents && otherStudents.length > 0" class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-blue-900">
                            Available Students in {{ classroom.grade?.name }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <button
                                @click="selectAllToAdd"
                                class="text-sm text-blue-700 hover:text-blue-900"
                            >
                                {{ allToAddSelected ? 'Deselect All' : 'Select All' }}
                            </button>
                            <button
                                v-if="selectedToAdd.length > 0"
                                @click="addSelectedStudents"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <CheckIcon class="w-4 h-4 mr-1" />
                                Add ({{ selectedToAdd.length }})
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="student in otherStudents"
                            :key="student.id"
                            @click="toggleAddStudent(student.id)"
                            :class="[
                                'inline-flex items-center px-3 py-1.5 text-sm rounded-lg border transition-colors',
                                isSelectedToAdd(student.id)
                                    ? 'bg-blue-600 text-white border-blue-600'
                                    : 'bg-white text-slate-700 border-slate-300 hover:border-blue-400'
                            ]"
                        >
                            <CheckIcon v-if="isSelectedToAdd(student.id)" class="w-3 h-3 mr-1" />
                            {{ student.name }}
                            <span v-if="student.classroom" class="ml-1 text-xs opacity-75">
                                ({{ student.classroom.name }})
                            </span>
                        </button>
                    </div>
                </div>

                <div v-else-if="showAddStudents && otherStudents.length === 0" class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                    <p class="text-sm text-blue-700">No available students to add. All students in this grade are already assigned to this classroom.</p>
                </div>

                <!-- Student List -->
                <div v-if="classroom.students?.length > 0">
                    <div class="px-6 py-2 bg-slate-50 border-b border-slate-200 flex items-center">
                        <button
                            @click="selectAllStudents"
                            class="flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900"
                        >
                            <input 
                                type="checkbox"
                                :checked="allSelected"
                                class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                readonly
                            />
                            <span>{{ allSelected ? 'Deselect All' : 'Select All' }}</span>
                        </button>
                    </div>
                    <div class="divide-y divide-slate-200">
                        <div 
                            v-for="student in classroom.students" 
                            :key="student.id"
                            :class="[
                                'flex items-center px-6 py-3 hover:bg-slate-50 transition-colors cursor-pointer',
                                isSelected(student.id) ? 'bg-brand-50' : ''
                            ]"
                            @click="toggleStudent(student.id)"
                        >
                            <input 
                                type="checkbox"
                                :checked="isSelected(student.id)"
                                class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 mr-4"
                                readonly
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="p-2 bg-slate-100 rounded-full">
                                    <UserIcon class="w-4 h-4 text-slate-500" />
                                </div>
                                <span class="text-sm font-medium text-slate-900">{{ student.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="p-12 text-center">
                    <UserGroupIcon class="mx-auto h-12 w-12 text-slate-400" />
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No students yet</h3>
                    <p class="mt-1 text-sm text-slate-500">Add students to this classroom using the button above.</p>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>

