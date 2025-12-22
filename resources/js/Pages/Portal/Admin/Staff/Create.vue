<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { computed } from 'vue';
import { 
    ChevronLeftIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    classrooms: Array,
    staffEmailDomain: String,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'teacher',
    classroom_ids: [],
    send_welcome_email: true,
});

const submit = () => {
    form.post('/portal/admin/staff');
};

const isTeacher = computed(() => form.role === 'teacher');

const toggleClassroom = (classroomId) => {
    const index = form.classroom_ids.indexOf(classroomId);
    if (index === -1) {
        form.classroom_ids.push(classroomId);
    } else {
        form.classroom_ids.splice(index, 1);
    }
};
</script>

<template>
    <Head title="Add Staff Member" />

    <PortalLayout>
        <template #header>Add Staff Member</template>

        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Breadcrumb -->
            <div>
                <Link 
                    href="/portal/admin/staff"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to Staff Management
                </Link>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">
                        Role
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label 
                            :class="[
                                'relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                                form.role === 'teacher' 
                                    ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-500/20' 
                                    : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                            ]"
                        >
                            <input 
                                type="radio" 
                                v-model="form.role" 
                                value="teacher" 
                                class="sr-only" 
                            />
                            <AcademicCapIcon 
                                :class="[
                                    'w-8 h-8 mb-2',
                                    form.role === 'teacher' ? 'text-emerald-600' : 'text-slate-400'
                                ]" 
                            />
                            <span 
                                :class="[
                                    'font-semibold',
                                    form.role === 'teacher' ? 'text-emerald-700' : 'text-slate-700'
                                ]"
                            >
                                Staff Member
                            </span>
                            <span class="text-xs text-slate-500 mt-1 text-center">
                                Can manage assigned classrooms
                            </span>
                        </label>
                        <label 
                            :class="[
                                'relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                                form.role === 'admin' 
                                    ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500/20' 
                                    : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                            ]"
                        >
                            <input 
                                type="radio" 
                                v-model="form.role" 
                                value="admin" 
                                class="sr-only" 
                            />
                            <ShieldCheckIcon 
                                :class="[
                                    'w-8 h-8 mb-2',
                                    form.role === 'admin' ? 'text-blue-600' : 'text-slate-400'
                                ]" 
                            />
                            <span 
                                :class="[
                                    'font-semibold',
                                    form.role === 'admin' ? 'text-blue-700' : 'text-slate-700'
                                ]"
                            >
                                Administrator
                            </span>
                            <span class="text-xs text-slate-500 mt-1 text-center">
                                Full portal access
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        :class="{ 'border-red-500': form.errors.name }"
                        placeholder="Jane Smith"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        :class="{ 'border-red-500': form.errors.email }"
                        :placeholder="`jane.smith@${staffEmailDomain}`"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            :class="{ 'border-red-500': form.errors.password }"
                            placeholder="••••••••"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <!-- Classroom Assignment (for teachers) -->
                <div v-if="isTeacher">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Assign to Classrooms
                    </label>
                    <div v-if="classrooms.length > 0" class="border border-slate-200 rounded-lg divide-y divide-slate-200 max-h-64 overflow-y-auto">
                        <label 
                            v-for="classroom in classrooms" 
                            :key="classroom.id"
                            class="flex items-center px-4 py-3 hover:bg-slate-50 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                :checked="form.classroom_ids.includes(classroom.id)"
                                @change="toggleClassroom(classroom.id)"
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded"
                            />
                            <span class="ml-3 text-sm text-slate-700">{{ classroom.display_name }}</span>
                        </label>
                    </div>
                    <p v-else class="text-sm text-slate-500 italic">No classrooms available. Create classrooms first.</p>
                    <p class="mt-2 text-xs text-slate-500">Select the classrooms this staff member will manage.</p>
                </div>

                <!-- Send Welcome Email -->
                <div class="flex items-center">
                    <input
                        id="send_welcome_email"
                        v-model="form.send_welcome_email"
                        type="checkbox"
                        class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded"
                    />
                    <label for="send_welcome_email" class="ml-2 text-sm text-slate-700">
                        Send welcome email with verification link
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-200">
                    <Link
                        href="/portal/admin/staff"
                        class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <span v-if="form.processing">Creating...</span>
                        <span v-else>Create Staff Member</span>
                    </button>
                </div>
            </form>
        </div>
    </PortalLayout>
</template>

