<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref } from 'vue';
import { 
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    UserGroupIcon,
    EyeIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    pendingUsers: Array,
    counts: Object,
});

const selectedUsers = ref([]);
const bulkApproving = ref(false);

const toggleSelectAll = () => {
    if (selectedUsers.value.length === props.pendingUsers.length) {
        selectedUsers.value = [];
    } else {
        selectedUsers.value = props.pendingUsers.map(u => u.id);
    }
};

const toggleUser = (userId) => {
    const index = selectedUsers.value.indexOf(userId);
    if (index > -1) {
        selectedUsers.value.splice(index, 1);
    } else {
        selectedUsers.value.push(userId);
    }
};

const approveUser = (userId) => {
    router.post(`/portal/admin/approvals/${userId}/approve`);
};

const rejectUser = (userId) => {
    if (confirm('Are you sure you want to reject this registration? This will delete the user account.')) {
        router.post(`/portal/admin/approvals/${userId}/reject`);
    }
};

const bulkApprove = () => {
    if (selectedUsers.value.length === 0) return;
    
    if (confirm(`Are you sure you want to approve ${selectedUsers.value.length} user(s)?`)) {
        bulkApproving.value = true;
        router.post('/portal/admin/approvals/bulk-approve', {
            user_ids: selectedUsers.value,
        }, {
            onFinish: () => {
                bulkApproving.value = false;
                selectedUsers.value = [];
            },
        });
    }
};

const formatDate = (date) => {
    if (!date) return 'Unknown';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Pending Approvals" />

    <PortalLayout>
        <template #header>Pending Approvals</template>

        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                            <ClockIcon class="w-6 h-6 text-amber-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.pending }}</p>
                            <p class="text-sm text-slate-500">Pending Approval</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <CheckCircleIcon class="w-6 h-6 text-green-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.approved_parents }}</p>
                            <p class="text-sm text-slate-500">Approved Parents</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center">
                            <UserGroupIcon class="w-6 h-6 text-brand-600" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ counts.total_parents }}</p>
                            <p class="text-sm text-slate-500">Total Parents</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div v-if="selectedUsers.length > 0" class="bg-brand-50 border border-brand-200 rounded-lg p-4 flex items-center justify-between">
                <span class="text-sm text-brand-800">
                    {{ selectedUsers.length }} user(s) selected
                </span>
                <button
                    @click="bulkApprove"
                    :disabled="bulkApproving"
                    class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50"
                >
                    {{ bulkApproving ? 'Approving...' : 'Approve Selected' }}
                </button>
            </div>

            <!-- Pending Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Pending Parent Registrations</h2>
                    <p class="text-sm text-slate-500 mt-1">Review and approve parent registrations</p>
                </div>

                <div v-if="pendingUsers.length === 0" class="px-6 py-12 text-center">
                    <CheckCircleIcon class="w-12 h-12 text-green-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-slate-900">All caught up!</h3>
                    <p class="text-slate-500 mt-1">There are no pending registrations to review.</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input
                                        type="checkbox"
                                        @change="toggleSelectAll"
                                        :checked="selectedUsers.length === pendingUsers.length"
                                        class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                    />
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Registered
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="user in pendingUsers" :key="user.id" class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <input
                                        type="checkbox"
                                        :checked="selectedUsers.includes(user.id)"
                                        @change="toggleUser(user.id)"
                                        class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                    />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">{{ user.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-500">{{ user.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-500">{{ user.created_at_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/portal/admin/approvals/${user.id}`"
                                            class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                            title="View details"
                                        >
                                            <EyeIcon class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="approveUser(user.id)"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Approve"
                                        >
                                            <CheckCircleIcon class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="rejectUser(user.id)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Reject"
                                        >
                                            <XCircleIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
