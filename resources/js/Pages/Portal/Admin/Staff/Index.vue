<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref } from 'vue';
import { 
    UserGroupIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
    PlusIcon,
    PencilIcon,
    TrashIcon,
    CheckCircleIcon,
    XCircleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    staff: Array,
    counts: Object,
});

const confirmingDelete = ref(null);
const deleteConfirmText = ref('');

const deleteStaff = (staffId) => {
    if (deleteConfirmText.value.toLowerCase() !== 'delete') {
        return;
    }
    router.delete(`/portal/admin/staff/${staffId}`, {
        onSuccess: () => {
            confirmingDelete.value = null;
            deleteConfirmText.value = '';
        },
    });
};

const cancelDelete = () => {
    confirmingDelete.value = null;
    deleteConfirmText.value = '';
};

const getRoleBadgeClass = (role) => {
    switch (role) {
        case 'super_admin':
            return 'bg-purple-100 text-purple-800';
        case 'admin':
            return 'bg-blue-100 text-blue-800';
        case 'teacher':
            return 'bg-emerald-100 text-emerald-800';
        default:
            return 'bg-slate-100 text-slate-800';
    }
};

const formatDate = (date) => {
    if (!date) return 'Never';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Staff Management" />

    <PortalLayout>
        <template #header>Staff Management</template>

        <div class="space-y-6">
            <!-- Header Actions -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600">Manage staff members, assign roles, and classroom assignments.</p>
                </div>
                <Link
                    href="/portal/admin/staff/create"
                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Add Staff Member
                </Link>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <UserGroupIcon class="w-6 h-6 text-slate-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.total }}</p>
                            <p class="text-sm text-slate-600">Total Staff</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <AcademicCapIcon class="w-6 h-6 text-emerald-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.teachers }}</p>
                            <p class="text-sm text-slate-600">Staff Members</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <ShieldCheckIcon class="w-6 h-6 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.admins }}</p>
                            <p class="text-sm text-slate-600">Administrators</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div v-if="staff.length === 0" class="p-12 text-center">
                    <UserGroupIcon class="mx-auto h-12 w-12 text-slate-400" />
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No staff members</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by adding your first staff member.</p>
                    <div class="mt-6">
                        <Link
                            href="/portal/admin/staff/create"
                            class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                        >
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Add Staff Member
                        </Link>
                    </div>
                </div>

                <table v-else class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Classrooms
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <tr v-for="member in staff" :key="member.id" class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-brand-700">
                                                {{ member.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ member.name }}</div>
                                        <div class="text-sm text-slate-500">{{ member.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        getRoleBadgeClass(member.role)
                                    ]"
                                >
                                    {{ member.role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="member.classrooms.length > 0" class="flex flex-wrap gap-1">
                                    <span 
                                        v-for="classroom in member.classrooms.slice(0, 2)" 
                                        :key="classroom.id"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700"
                                    >
                                        {{ classroom.grade_name }} - {{ classroom.name }}
                                    </span>
                                    <span 
                                        v-if="member.classrooms.length > 2"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-500"
                                    >
                                        +{{ member.classrooms.length - 2 }} more
                                    </span>
                                </div>
                                <span v-else class="text-sm text-slate-400">—</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1">
                                    <CheckCircleIcon 
                                        v-if="member.email_verified_at" 
                                        class="w-4 h-4 text-emerald-500" 
                                        title="Email verified"
                                    />
                                    <XCircleIcon 
                                        v-else 
                                        class="w-4 h-4 text-amber-500" 
                                        title="Email not verified"
                                    />
                                    <span class="text-sm text-slate-500">
                                        {{ member.email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/portal/admin/staff/${member.id}/edit`"
                                        class="px-3 py-1 text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-md transition-colors"
                                    >
                                        Edit
                                    </Link>
                                    <template v-if="member.role !== 'super_admin'">
                                        <button
                                            v-if="confirmingDelete !== member.id"
                                            @click="confirmingDelete = member.id"
                                            class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-md transition-colors"
                                        >
                                            Delete
                                        </button>
                                        <div v-else class="flex items-center gap-2">
                                            <input
                                                v-model="deleteConfirmText"
                                                type="text"
                                                placeholder="Type 'delete'"
                                                class="w-24 px-2 py-1 text-xs border border-red-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                                @keyup.enter="deleteStaff(member.id)"
                                            />
                                            <button
                                                @click="deleteStaff(member.id)"
                                                :disabled="deleteConfirmText.toLowerCase() !== 'delete'"
                                                :class="[
                                                    'px-3 py-1 text-xs font-medium rounded-md transition-colors',
                                                    deleteConfirmText.toLowerCase() === 'delete'
                                                        ? 'text-white bg-red-600 hover:bg-red-700'
                                                        : 'text-red-300 bg-red-100 cursor-not-allowed'
                                                ]"
                                            >
                                                Confirm
                                            </button>
                                            <button
                                                @click="cancelDelete"
                                                class="px-3 py-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PortalLayout>
</template>

