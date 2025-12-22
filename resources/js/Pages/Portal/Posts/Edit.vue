<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import PostForm from '@/Components/Portal/PostForm.vue';

const props = defineProps({
    post: Object,
    grades: Array,
    teachers: Array,
});

// Format datetime for input fields (YYYY-MM-DDTHH:MM)
const formatDateTimeLocal = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

// Format date for input fields (YYYY-MM-DD)
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const form = useForm({
    type: props.post.type,
    is_school_closure: props.post.is_school_closure || false,
    audience: props.post.audience || 'all',
    target_grade_id: props.post.target_grade_id || null,
    target_teacher_id: props.post.target_teacher_id || null,
    title: props.post.title,
    content: props.post.content,
    image: null,
    remove_image: false,
    event_start_date: formatDateTimeLocal(props.post.event_start_date),
    event_end_date: formatDateTimeLocal(props.post.event_end_date),
    button_text: props.post.button_text || '',
    button_url: props.post.button_url || '',
    recurrence_type: props.post.recurrence_type || 'none',
    recurrence_end_date: formatDate(props.post.recurrence_end_date),
    published_at: props.post.published_at,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(`/portal/posts/${props.post.slug}`, {
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
                    :grades="grades"
                    :teachers="teachers"
                    @submit="submit" 
                    mode="edit" 
                />
            </div>
        </div>
    </PortalLayout>
</template>

