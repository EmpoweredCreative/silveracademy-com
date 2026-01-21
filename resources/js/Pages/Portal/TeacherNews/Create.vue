<script setup>
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { computed } from 'vue';
import { 
    MegaphoneIcon,
    ArrowLeftIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    grades: Array,
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);

const form = useForm({
    title: '',
    content: '',
    target_grade_id: props.grades?.[0]?.id || '',
});

const submit = () => {
    form.post('/portal/teacher-news', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Post Grade News" />

    <PortalLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link href="/portal/dashboard" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <ArrowLeftIcon class="w-6 h-6" />
                </Link>
                <span>Post News to Parents</span>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <!-- Success/Error Messages -->
            <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <CheckCircleIcon class="w-5 h-5 text-green-600" />
                    <p class="text-sm text-green-800">{{ successMessage }}</p>
                </div>
            </div>
            <div v-if="errorMessage" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <ExclamationTriangleIcon class="w-5 h-5 text-red-600" />
                    <p class="text-sm text-red-800">{{ errorMessage }}</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <MegaphoneIcon class="w-5 h-5 text-emerald-600 mt-0.5" />
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Share news with parents</p>
                        <p class="text-sm text-emerald-700 mt-1">
                            This news will be shown to parents who have children in the selected grade level. 
                            It will appear on their portal dashboard.
                        </p>
                    </div>
                </div>
            </div>

            <!-- No Grades Warning -->
            <div v-if="!grades || grades.length === 0" class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
                <ExclamationTriangleIcon class="w-12 h-12 text-amber-500 mx-auto mb-3" />
                <h3 class="text-lg font-semibold text-amber-800">No Grades Assigned</h3>
                <p class="text-amber-700 mt-2">
                    You are not currently assigned to any grades. Please contact an administrator to be assigned to a grade level.
                </p>
                <Link href="/portal/dashboard" class="inline-block mt-4 text-amber-700 hover:text-amber-800 font-medium">
                    ← Back to Dashboard
                </Link>
            </div>

            <!-- Form -->
            <form v-else @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">New Grade News</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Grade Selection -->
                    <div>
                        <label for="target_grade_id" class="block text-sm font-medium text-slate-700 mb-1">
                            Select Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="target_grade_id"
                            v-model="form.target_grade_id"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            :class="{ 'border-red-500': form.errors.target_grade_id }"
                        >
                            <option v-for="grade in grades" :key="grade.id" :value="grade.id">
                                {{ grade.name }}
                            </option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500">
                            Only parents with children in this grade will see this news.
                        </p>
                        <p v-if="form.errors.target_grade_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.target_grade_id }}
                        </p>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                            News Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="e.g., Field Trip Permission Slips Due"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            :class="{ 'border-red-500': form.errors.title }"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="6"
                            required
                            placeholder="Write your message to parents..."
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                            :class="{ 'border-red-500': form.errors.content }"
                        ></textarea>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                            {{ form.errors.content }}
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <Link
                        href="/portal/dashboard"
                        class="px-4 py-2 text-slate-600 hover:text-slate-800 font-medium transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        {{ form.processing ? 'Posting...' : 'Post to Parents' }}
                    </button>
                </div>
            </form>
        </div>
    </PortalLayout>
</template>
