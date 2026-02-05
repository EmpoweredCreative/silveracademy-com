<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    news: Array,
    events: Array,
});

const activeTab = ref('all');

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'America/New_York',
    });
};

const formatEventDate = (startDate, endDate) => {
    const start = new Date(startDate);
    const options = { month: 'short', day: 'numeric', year: 'numeric', timeZone: 'America/New_York' };
    
    if (!endDate) {
        return start.toLocaleDateString('en-US', options);
    }
    
    const end = new Date(endDate);
    // Compare dates in Eastern Time
    const startET = new Date(start.toLocaleString('en-US', { timeZone: 'America/New_York' }));
    const endET = new Date(end.toLocaleString('en-US', { timeZone: 'America/New_York' }));
    
    if (startET.toDateString() === endET.toDateString()) {
        return start.toLocaleDateString('en-US', options);
    }
    
    // Check if same month/year in Eastern Time
    if (startET.getMonth() === endET.getMonth() && startET.getFullYear() === endET.getFullYear()) {
        return `${start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', timeZone: 'America/New_York' })} - ${endET.getDate()}, ${endET.getFullYear()}`;
    }
    
    return `${start.toLocaleDateString('en-US', options)} - ${end.toLocaleDateString('en-US', options)}`;
};

const stripHtml = (html) => {
    if (!html) return '';
    // Replace block-level tags with spaces to prevent words from running together
    let text = html
        .replace(/<\/?(p|div|br|li|ul|ol|h[1-6])[^>]*>/gi, ' ')
        .replace(/<[^>]+>/g, '') // Remove remaining tags
        .replace(/&nbsp;/g, ' ')
        .replace(/\s+/g, ' ') // Collapse multiple spaces
        .trim();
    return text;
};

const getExcerpt = (content, length = 150) => {
    const text = stripHtml(content);
    if (text.length <= length) return text;
    return text.substring(0, length).trim() + '...';
};
</script>

<template>
    <Head title="News & Events" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-brand-600 to-brand-800 py-16 lg:py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight">
                        News & Events
                    </h1>
                    <p class="mt-6 text-lg text-brand-100 max-w-2xl mx-auto">
                        Stay connected with the latest happenings at The Silver Academy. 
                        From school announcements to upcoming events, find everything here.
                    </p>
                </div>
            </div>
        </section>

        <!-- Tabs -->
        <section class="bg-white border-b border-slate-200 sticky top-[72px] z-40">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex gap-8">
                    <button
                        @click="activeTab = 'all'"
                        :class="[
                            'py-4 text-sm font-medium uppercase tracking-wider border-b-2 transition-colors',
                            activeTab === 'all'
                                ? 'border-brand-600 text-brand-600'
                                : 'border-transparent text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        All
                    </button>
                    <button
                        @click="activeTab = 'news'"
                        :class="[
                            'py-4 text-sm font-medium uppercase tracking-wider border-b-2 transition-colors',
                            activeTab === 'news'
                                ? 'border-brand-600 text-brand-600'
                                : 'border-transparent text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        News
                    </button>
                    <button
                        @click="activeTab = 'events'"
                        :class="[
                            'py-4 text-sm font-medium uppercase tracking-wider border-b-2 transition-colors',
                            activeTab === 'events'
                                ? 'border-brand-600 text-brand-600'
                                : 'border-transparent text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        Events
                    </button>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-12">
                    <!-- Left Column - Upcoming Events (wider) -->
                    <div class="hidden lg:block lg:col-span-2" v-if="activeTab !== 'events'">
                        <div class="sticky top-32">
                            <h2 class="font-serif text-2xl text-slate-800 mb-6">Upcoming Events</h2>
                            
                            <div v-if="events.length === 0" class="text-center py-8 bg-white rounded-xl">
                                <p class="text-slate-500 text-sm">No upcoming events.</p>
                            </div>

                            <div v-else class="space-y-4">
                                <Link
                                    v-for="event in events.slice(0, 5)"
                                    :key="event.id"
                                    :href="`/events/${event.slug}`"
                                    class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 group"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-14 text-center bg-brand-50 rounded-lg py-2">
                                            <div class="text-xl font-bold text-brand-600">
                                                {{ new Date(event.event_start_date).getDate() }}
                                            </div>
                                            <div class="text-xs text-brand-500 uppercase">
                                                {{ new Date(event.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-slate-800 group-hover:text-brand-600 transition-colors truncate">
                                                {{ event.title }}
                                            </h3>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ formatEventDate(event.event_start_date, event.event_end_date) }}
                                            </p>
                                        </div>
                                    </div>
                                </Link>

                                <button
                                    v-if="events.length > 5"
                                    @click="activeTab = 'events'"
                                    class="w-full py-3 text-sm font-medium text-brand-600 hover:text-brand-700 bg-brand-50 rounded-lg transition-colors"
                                >
                                    View All Events
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Main Content (News / Events tab) - narrower -->
                    <div class="lg:col-span-1">
                        <!-- News Section -->
                        <div v-if="activeTab === 'all' || activeTab === 'news'" class="mb-12">
                            <h2 v-if="activeTab === 'all'" class="font-serif text-2xl text-slate-800 mb-6">
                                Latest News
                            </h2>
                            
                            <div v-if="news.length === 0" class="text-center py-12 bg-white rounded-xl">
                                <p class="text-slate-500">No news articles yet. Check back soon!</p>
                            </div>

                            <div v-else class="space-y-6">
                                <Link
                                    v-for="article in news"
                                    :key="article.id"
                                    :href="`/news/${article.slug}`"
                                    class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group"
                                >
                                    <div class="flex flex-col md:flex-row">
                                        <div v-if="article.image_path" class="md:w-48 md:flex-shrink-0">
                                            <img
                                                :src="`/storage/${article.image_path}`"
                                                :alt="article.title"
                                                class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            />
                                        </div>
                                        <div class="p-6 flex-1">
                                            <p class="text-sm text-brand-600 font-medium mb-2">
                                                {{ formatDate(article.published_at) }}
                                            </p>
                                            <h3 class="font-serif text-xl text-slate-800 group-hover:text-brand-600 transition-colors mb-2">
                                                {{ article.title }}
                                            </h3>
                                            <p class="text-slate-600 text-sm">
                                                {{ getExcerpt(article.content) }}
                                            </p>
                                            <p class="mt-4 text-sm font-medium text-brand-600 group-hover:text-brand-700">
                                                Read more →
                                            </p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Events Section (when showing all) -->
                        <div v-if="activeTab === 'all'" class="lg:hidden">
                            <h2 class="font-serif text-2xl text-slate-800 mb-6">Upcoming Events</h2>
                            
                            <div v-if="events.length === 0" class="text-center py-12 bg-white rounded-xl">
                                <p class="text-slate-500">No upcoming events. Check back soon!</p>
                            </div>

                            <div v-else class="space-y-4">
                                <Link
                                    v-for="event in events"
                                    :key="event.id"
                                    :href="`/events/${event.slug}`"
                                    class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 group"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-16 text-center">
                                            <div class="text-3xl font-bold text-brand-600">
                                                {{ new Date(event.event_start_date).getDate() }}
                                            </div>
                                            <div class="text-sm text-slate-500 uppercase">
                                                {{ new Date(event.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-serif text-lg text-slate-800 group-hover:text-brand-600 transition-colors">
                                                {{ event.title }}
                                            </h3>
                                            <p class="text-sm text-slate-500 mt-1">
                                                {{ formatEventDate(event.event_start_date, event.event_end_date) }}
                                            </p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Events Tab Content -->
                        <div v-if="activeTab === 'events'">
                            <div v-if="events.length === 0" class="text-center py-12 bg-white rounded-xl">
                                <p class="text-slate-500">No upcoming events. Check back soon!</p>
                            </div>

                            <div v-else class="space-y-6">
                                <Link
                                    v-for="event in events"
                                    :key="event.id"
                                    :href="`/events/${event.slug}`"
                                    class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group"
                                >
                                    <div class="flex flex-col md:flex-row">
                                        <div class="md:w-32 bg-brand-600 text-white p-6 flex flex-col items-center justify-center">
                                            <div class="text-4xl font-bold">
                                                {{ new Date(event.event_start_date).getDate() }}
                                            </div>
                                            <div class="text-sm uppercase tracking-wider">
                                                {{ new Date(event.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                            </div>
                                            <div class="text-sm">
                                                {{ new Date(event.event_start_date).getFullYear() }}
                                            </div>
                                        </div>
                                        <div class="p-6 flex-1">
                                            <h3 class="font-serif text-xl text-slate-800 group-hover:text-brand-600 transition-colors mb-2">
                                                {{ event.title }}
                                            </h3>
                                            <p class="text-slate-600 text-sm mb-4">
                                                {{ getExcerpt(event.content, 200) }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ formatEventDate(event.event_start_date, event.event_end_date) }}
                                            </p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>



