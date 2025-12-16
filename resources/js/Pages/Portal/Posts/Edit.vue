<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import PostForm from '@/Components/Portal/PostForm.vue';

const props = defineProps({
    post: Object,
});

const form = useForm({
    type: props.post.type,
    title: props.post.title,
    content: props.post.content,
    image: null,
    remove_image: false,
    event_start_date: props.post.event_start_date ? props.post.event_start_date.split('T')[0] : '',
    event_end_date: props.post.event_end_date ? props.post.event_end_date.split('T')[0] : '',
    button_text: props.post.button_text || '',
    button_url: props.post.button_url || '',
    published_at: props.post.published_at,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(`/portal/posts/${props.post.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Post" />

    <PortalLayout>
        <template #header>Edit Post</template>
        
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
                <p class="text-slate-600">Update your news article or event.</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <PostForm 
                    :form="form" 
                    :post="post"
                    @submit="submit" 
                    mode="edit" 
                />
            </div>
        </div>
    </PortalLayout>
</template>

