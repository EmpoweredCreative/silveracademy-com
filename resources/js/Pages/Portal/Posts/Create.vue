<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import PostForm from '@/Components/Portal/PostForm.vue';

const props = defineProps({
    grades: Array,
    teachers: Array,
});

const form = useForm({
    type: 'news',
    is_school_closure: false,
    is_public: false,
    audience: 'all',
    target_grade_id: null,
    target_teacher_id: null,
    title: '',
    content: '',
    image: null,
    event_start_date: '',
    event_end_date: '',
    button_text: '',
    button_url: '',
    recurrence_type: 'none',
    recurrence_end_date: '',
    publish_now: true,
});

const submit = () => {
    console.log('Submitting form with data:', {
        type: form.type,
        title: form.title,
        content: form.content ? form.content.substring(0, 100) + '...' : '(empty)',
        is_public: form.is_public,
        event_start_date: form.event_start_date,
        event_end_date: form.event_end_date,
    });
    
    form.post('/portal/posts', {
        forceFormData: true,
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
        onSuccess: () => {
            console.log('Form submitted successfully!');
        },
    });
};
</script>

<template>
    <Head title="Create Post" />

    <PortalLayout>
        <template #header>Create New Post</template>
        
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <Link
                    href="/portal/posts"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-4"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Posts
                </Link>
                <p class="text-slate-600">Create a news article or event to share with your community.</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <PostForm 
                    :form="form" 
                    :grades="grades" 
                    :teachers="teachers" 
                    @submit="submit" 
                    mode="create" 
                />
            </div>
        </div>
    </PortalLayout>
</template>

