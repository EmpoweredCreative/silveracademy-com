<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';

const form = useForm({
    week_start: '',
    content: '',
});

const submit = () => {
    form.post('/portal/lunch');
};

// Get next Monday
const getNextMonday = () => {
    const today = new Date();
    const dayOfWeek = today.getDay();
    const daysUntilMonday = dayOfWeek === 0 ? 1 : (8 - dayOfWeek);
    const nextMonday = new Date(today);
    nextMonday.setDate(today.getDate() + daysUntilMonday);
    return nextMonday.toISOString().split('T')[0];
};

// Set default to next Monday
form.week_start = getNextMonday();
</script>

<template>
    <Head title="Add Lunch Menu" />

    <PortalLayout>
        <template #header>Add Lunch Menu</template>
        
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
                <p class="text-slate-600">Create a new weekly lunch menu for parents to view.</p>
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
                        <p class="mt-1 text-sm text-slate-500">Select the Monday of the week this menu is for.</p>
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
                            placeholder="Monday:
- Chicken nuggets
- Mashed potatoes
- Green beans
- Fruit cup

Tuesday:
- Pizza
- Side salad
- Apple slices

..."
                            required
                        ></textarea>
                        <p class="mt-1 text-sm text-slate-500">Enter the lunch menu for each day of the week. HTML formatting is supported.</p>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-200">
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
                            <span v-else>Save Menu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </PortalLayout>
</template>



