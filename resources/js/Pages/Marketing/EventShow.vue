<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const props = defineProps({
    event: Object,
    upcomingEvents: Array,
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const formatTime = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    });
};

const formatEventDate = (startDate, endDate) => {
    const start = new Date(startDate);
    
    if (!endDate) {
        return formatDate(startDate);
    }
    
    const end = new Date(endDate);
    
    if (start.toDateString() === end.toDateString()) {
        return formatDate(startDate);
    }
    
    return `${formatDate(startDate)} - ${formatDate(endDate)}`;
};

const formatEventTime = (startDate, endDate) => {
    const start = new Date(startDate);
    
    if (!endDate) {
        return formatTime(startDate);
    }
    
    const end = new Date(endDate);
    
    if (start.toDateString() === end.toDateString()) {
        return `${formatTime(startDate)} - ${formatTime(endDate)}`;
    }
    
    return `Starting at ${formatTime(startDate)}`;
};

const getExcerpt = (content, length = 100) => {
    if (content.length <= length) return content;
    return content.substring(0, length).trim() + '...';
};
</script>

<template>
    <Head :title="event.title" />

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
                    <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-accent-200 bg-accent-600/50 rounded-full mb-4">
                        Event
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight">
                        {{ event.title }}
                    </h1>
                </div>
            </div>
        </section>

        <!-- Event Details Card -->
        <section class="bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 -mt-8">
                <div class="bg-white rounded-xl shadow-xl p-6 lg:p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Date</p>
                                <p class="text-slate-800 font-medium">
                                    {{ formatEventDate(event.event_start_date, event.event_end_date) }}
                                </p>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Time</p>
                                <p class="text-slate-800 font-medium">
                                    {{ formatEventTime(event.event_start_date, event.event_end_date) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Button -->
                    <div v-if="event.button_text && event.button_url" class="mt-8 pt-6 border-t border-slate-200">
                        <a
                            :href="event.button_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-full md:w-auto px-8 py-4 bg-accent-500 text-white font-semibold rounded-lg hover:bg-accent-600 transition-colors text-lg"
                        >
                            {{ event.button_text }}
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Image -->
        <section v-if="event.image_path" class="py-8 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <img
                    :src="`/storage/${event.image_path}`"
                    :alt="event.title"
                    class="w-full rounded-xl shadow-lg"
                />
            </div>
        </section>

        <!-- Content -->
        <section class="py-12 lg:py-16 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <h2 class="font-serif text-2xl text-slate-800 mb-6">About This Event</h2>
                <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-slate-800 prose-p:text-slate-600 prose-a:text-brand-600 prose-a:no-underline hover:prose-a:underline">
                    <div class="whitespace-pre-wrap">{{ event.content }}</div>
                </div>
            </div>
        </section>

        <!-- Add to Calendar -->
        <section class="py-8 bg-slate-50 border-y border-slate-200">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-slate-600">
                        Add this event to your calendar:
                    </div>
                    <div class="flex gap-3">
                        <a
                            :href="`https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${new Date(event.event_start_date).toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${new Date(event.event_end_date || event.event_start_date).toISOString().replace(/[-:]/g, '').split('.')[0]}Z&details=${encodeURIComponent(event.content.substring(0, 200))}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-4 py-2 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow text-sm font-medium text-slate-700 hover:text-brand-600"
                        >
                            Google Calendar
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- More Upcoming Events -->
        <section v-if="upcomingEvents.length > 0" class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-2xl text-slate-800 mb-8 text-center">More Upcoming Events</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <Link
                        v-for="upcomingEvent in upcomingEvents"
                        :key="upcomingEvent.id"
                        :href="`/events/${upcomingEvent.slug}`"
                        class="group"
                    >
                        <div class="bg-slate-50 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex-shrink-0 w-14 text-center bg-brand-100 rounded-lg py-2">
                                        <div class="text-xl font-bold text-brand-600">
                                            {{ new Date(upcomingEvent.event_start_date).getDate() }}
                                        </div>
                                        <div class="text-xs text-brand-500 uppercase">
                                            {{ new Date(upcomingEvent.event_start_date).toLocaleDateString('en-US', { month: 'short' }) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-serif text-lg text-slate-800 group-hover:text-brand-600 transition-colors">
                                            {{ upcomingEvent.title }}
                                        </h3>
                                    </div>
                                </div>
                                <p class="text-slate-600 text-sm">
                                    {{ getExcerpt(upcomingEvent.content) }}
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
                <h2 class="font-serif text-3xl text-white mb-4">Join Our Community</h2>
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



