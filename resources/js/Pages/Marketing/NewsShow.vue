<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const props = defineProps({
    post: Object,
    relatedNews: Array,
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const getExcerpt = (content, length = 100) => {
    if (content.length <= length) return content;
    return content.substring(0, length).trim() + '...';
};
</script>

<template>
    <Head :title="post.title" />

    <MarketingLayout>
        <!-- Hero/Header -->
        <section class="bg-gradient-to-br from-brand-600 to-brand-800 py-12 lg:py-16">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <Link
                    href="/news-events"
                    class="inline-flex items-center text-brand-200 hover:text-white transition-colors mb-6"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to News & Events
                </Link>
                
                <div class="text-center">
                    <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand-200 bg-brand-700/50 rounded-full mb-4">
                        News
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight">
                        {{ post.title }}
                    </h1>
                    <div class="mt-6 flex items-center justify-center gap-4 text-brand-200">
                        <span>{{ formatDate(post.published_at) }}</span>
                        <span v-if="post.author">•</span>
                        <span v-if="post.author">By {{ post.author.name }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Image -->
        <section v-if="post.image_path" class="bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 -mt-8">
                <img
                    :src="`/storage/${post.image_path}`"
                    :alt="post.title"
                    class="w-full rounded-xl shadow-xl"
                />
            </div>
        </section>

        <!-- Content -->
        <section class="py-12 lg:py-16 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-slate-800 prose-p:text-slate-600 prose-a:text-brand-600 prose-a:no-underline hover:prose-a:underline">
                    <div class="whitespace-pre-wrap">{{ post.content }}</div>
                </div>
            </div>
        </section>

        <!-- Share Section -->
        <section class="py-8 bg-slate-50 border-y border-slate-200">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Share this article:
                    </div>
                    <div class="flex gap-3">
                        <a
                            :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow text-slate-600 hover:text-brand-600"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a
                            :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(post.title)}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow text-slate-600 hover:text-brand-600"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a
                            :href="`mailto:?subject=${encodeURIComponent(post.title)}&body=${encodeURIComponent(window.location.href)}`"
                            class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow text-slate-600 hover:text-brand-600"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related News -->
        <section v-if="relatedNews.length > 0" class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-2xl text-slate-800 mb-8 text-center">More News</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <Link
                        v-for="article in relatedNews"
                        :key="article.id"
                        :href="`/news/${article.slug}`"
                        class="group"
                    >
                        <div class="bg-slate-50 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <div v-if="article.image_path" class="aspect-video overflow-hidden">
                                <img
                                    :src="`/storage/${article.image_path}`"
                                    :alt="article.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-brand-600 font-medium mb-2">
                                    {{ formatDate(article.published_at) }}
                                </p>
                                <h3 class="font-serif text-lg text-slate-800 group-hover:text-brand-600 transition-colors mb-2">
                                    {{ article.title }}
                                </h3>
                                <p class="text-slate-600 text-sm">
                                    {{ getExcerpt(article.content) }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 bg-brand-600">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl text-white mb-4">Stay Connected</h2>
                <p class="text-brand-100 mb-8">
                    Want to learn more about The Silver Academy? Schedule a visit today.
                </p>
                <a
                    href="https://calendar.app.google/Y5NrAjA9RooWgwZ98"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-block px-8 py-3 bg-accent-500 text-white font-semibold rounded-lg hover:bg-accent-600 transition-colors"
                >
                    Schedule a Visit
                </a>
            </div>
        </section>
    </MarketingLayout>
</template>



