<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed } from 'vue';
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    entries: Array,
});

const page = usePage();
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);

const confirmingDelete = ref(null);
const deleteConfirmText = ref('');

const deleteEntry = (id) => {
    if (deleteConfirmText.value.toLowerCase() !== 'delete') return;
    router.delete(`/portal/admin/staff-directory/${id}`, {
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
</script>

<template>
    <Head title="Staff Directory" />

    <PortalLayout>
        <template #header>Staff Directory</template>

        <div class="space-y-6">
            <p class="text-slate-600">
                This list appears on the public <a href="/staff" target="_blank" rel="noopener" class="text-brand-600 hover:underline">Staff page</a>. Data is stored in the database; add or edit entries here.
            </p>

            <!-- Flash messages -->
            <div v-if="success" class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm">
                {{ success }}
            </div>
            <div v-if="error" class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
                {{ error }}
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <Link
                    href="/portal/admin/staff-directory/create"
                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Add entry
                </Link>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Photo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="entry in entries" :key="entry.id" class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img
                                    v-if="entry.photo"
                                    :src="entry.photo"
                                    :alt="entry.name"
                                    class="h-12 w-12 rounded-full object-cover"
                                />
                                <span v-else class="text-slate-400 text-sm">—</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ entry.name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ entry.title }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ entry.department }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <Link
                                    :href="`/portal/admin/staff-directory/${entry.id}/edit`"
                                    class="inline-flex items-center text-slate-600 hover:text-brand-600 mr-3"
                                >
                                    <PencilIcon class="w-4 h-4" />
                                </Link>
                                <button
                                    type="button"
                                    @click="confirmingDelete = entry.id; deleteConfirmText = ''"
                                    class="text-slate-600 hover:text-red-600"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="entries.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No entries yet. Run <code class="bg-slate-100 px-1 rounded">php artisan db:seed</code> to seed the staff directory, or add entries above.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete confirmation modal -->
            <div
                v-if="confirmingDelete"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                @click.self="cancelDelete"
            >
                <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
                    <p class="text-slate-700 mb-4">Type <strong>delete</strong> to confirm removal from the public staff page.</p>
                    <input
                        v-model="deleteConfirmText"
                        type="text"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="delete"
                    />
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            type="button"
                            @click="cancelDelete"
                            class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="deleteEntry(confirmingDelete)"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                            :disabled="deleteConfirmText.toLowerCase() !== 'delete'"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
