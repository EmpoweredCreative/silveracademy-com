<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    menu: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    week_start: props.menu.week_start,
    content: props.menu.content,
});

const confirmingDelete = ref(false);

const submit = () => {
    form.put(`/portal/lunch/${props.menu.id}`);
};

const deleteMenu = () => {
    router.delete(`/portal/lunch/${props.menu.id}`);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { 
        weekday: 'long',
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Edit Lunch Menu" />

    <PortalLayout>
        <template #header>Edit Lunch Menu</template>
        
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <Link
                    href="/portal/lunch"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-4"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Lunch Menus
                </Link>
                <p class="text-slate-600">Update the lunch menu for week of {{ formatDate(menu.week_start) }}.</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Week Start Date -->
                    <div>
                        <label for="week_start" class="block text-sm font-medium text-slate-700 mb-1">
                            Week Starting (Monday) <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="week_start"
                            type="date"
                            v-model="form.week_start"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            required
                        />
                        <p v-if="form.errors.week_start" class="mt-1 text-sm text-red-600">{{ form.errors.week_start }}</p>
                    </div>

                    <!-- Menu Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                            Menu Content <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="12"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            required
                        ></textarea>
                        <p class="mt-1 text-sm text-slate-500">Enter the lunch menu for each day of the week. HTML formatting is supported.</p>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                        <div>
                            <button
                                v-if="!confirmingDelete"
                                type="button"
                                @click="confirmingDelete = true"
                                class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700"
                            >
                                Delete Menu
                            </button>
                            <div v-else class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="deleteMenu"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                >
                                    Confirm Delete
                                </button>
                                <button
                                    type="button"
                                    @click="confirmingDelete = false"
                                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-700"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <Link
                                href="/portal/lunch"
                                class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="form.processing">Saving...</span>
                                <span v-else>Save Changes</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </PortalLayout>
</template>



