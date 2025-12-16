<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    posts: Object,
    filters: Object,
});

const confirmingDelete = ref(null);

const deletePost = (postId) => {
    router.delete(`/portal/posts/${postId}`, {
        onSuccess: () => {
            confirmingDelete.value = null;
        },
    });
};

const togglePublish = (postId) => {
    router.post(`/portal/posts/${postId}/toggle-publish`);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Manage Posts" />

    <PortalLayout>
        <template #header>News & Events</template>
        
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600">Manage news articles and upcoming events.</p>
                </div>
                <Link
                    href="/portal/posts/create"
                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Post
                </Link>
            </div>

            <!-- Posts Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div v-if="posts.data.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No posts yet</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by creating your first news article or event.</p>
                    <div class="mt-6">
                        <Link
                            href="/portal/posts/create"
                            class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                        >
                            Create Post
                        </Link>
                    </div>
                </div>

                <table v-else class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <tr v-for="post in posts.data" :key="post.id" class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div v-if="post.image_path" class="flex-shrink-0 h-10 w-10">
                                        <img 
                                            :src="`/storage/${post.image_path}`" 
                                            :alt="post.title"
                                            class="h-10 w-10 rounded-lg object-cover"
                                        />
                                    </div>
                                    <div :class="post.image_path ? 'ml-4' : ''">
                                        <div class="text-sm font-medium text-slate-900">{{ post.title }}</div>
                                        <div class="text-sm text-slate-500">{{ post.author?.name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        post.type === 'news' 
                                            ? 'bg-blue-100 text-blue-800' 
                                            : 'bg-purple-100 text-purple-800'
                                    ]"
                                >
                                    {{ post.type === 'news' ? 'News' : 'Event' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        post.published_at 
                                            ? 'bg-green-100 text-green-800' 
                                            : 'bg-yellow-100 text-yellow-800'
                                    ]"
                                >
                                    {{ post.published_at ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <div v-if="post.type === 'event' && post.event_start_date">
                                    {{ formatDate(post.event_start_date) }}
                                    <span v-if="post.event_end_date"> - {{ formatDate(post.event_end_date) }}</span>
                                </div>
                                <div v-else>
                                    {{ formatDate(post.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="togglePublish(post.id)"
                                        :class="[
                                            'px-3 py-1 text-xs font-medium rounded-md transition-colors',
                                            post.published_at
                                                ? 'text-yellow-700 bg-yellow-50 hover:bg-yellow-100'
                                                : 'text-green-700 bg-green-50 hover:bg-green-100'
                                        ]"
                                    >
                                        {{ post.published_at ? 'Unpublish' : 'Publish' }}
                                    </button>
                                    <Link
                                        :href="`/portal/posts/${post.id}/edit`"
                                        class="px-3 py-1 text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-md transition-colors"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="confirmingDelete !== post.id"
                                        @click="confirmingDelete = post.id"
                                        class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-md transition-colors"
                                    >
                                        Delete
                                    </button>
                                    <div v-else class="flex items-center gap-1">
                                        <button
                                            @click="deletePost(post.id)"
                                            class="px-3 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors"
                                        >
                                            Confirm
                                        </button>
                                        <button
                                            @click="confirmingDelete = null"
                                            class="px-3 py-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="posts.data.length > 0 && posts.last_page > 1" class="px-6 py-4 border-t border-slate-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600">
                            Showing {{ posts.from }} to {{ posts.to }} of {{ posts.total }} results
                        </p>
                        <div class="flex gap-2">
                            <Link
                                v-for="link in posts.links"
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    'px-3 py-1 text-sm rounded-md transition-colors',
                                    link.active
                                        ? 'bg-brand-600 text-white'
                                        : link.url
                                            ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                            : 'bg-slate-50 text-slate-400 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>

