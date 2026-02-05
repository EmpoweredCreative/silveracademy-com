<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const highlights = [
    { title: 'Homework Help', description: 'Students receive guidance and support to complete assignments and reinforce learning from the classroom.' },
    { title: 'Creative Activities', description: 'Art, music, and hands-on projects give students a chance to express themselves and explore new interests.' },
    { title: 'Enrichment Options', description: 'From STEM activities to games and team-building exercises, students can discover and develop new skills.' },
    { title: 'Flexible Scheduling', description: 'Families can enroll for the year, by the month, or drop in for a single day as needed.' },
];

const reasons = [
    { title: 'Safe & Structured', description: 'Provides a safe and structured environment for students after school.' },
    { title: 'Exploration & Connection', description: 'Encourages exploration, creativity, and social connection.' },
    { title: 'Support for Families', description: 'Supports working families with flexible scheduling options.' }
];
</script>

<template>
    <Head title="After School Programming - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section - Image only -->
        <section class="relative h-[400px] overflow-hidden">
            <img 
                src="/img/graphics/programs/upper school/web-silveracademy2025_Silver Academy-894.jpg" 
                alt="After School Activities" 
                class="w-full h-full object-cover" 
                style="object-position: center 30%;"
            />
        </section>
        <!-- Hero Title - Below image -->
        <section class="py-8 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight tracking-tight">
                    Safe, Enriching, and Fun After the School Day
                </h1>
            </div>
        </section>

        <!-- Overview -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-xl text-slate-600 leading-relaxed mb-6">
                        A place where students can learn, play, and explore in a supportive environment.
                    </p>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        The Silver Academy offers a full after-school program Monday through Thursday until 6:00&nbsp;PM, 
                        providing a safe, engaging, and nurturing environment for students to continue learning, 
                        play creatively, and unwind after their school day.
                    </p>
                </div>
            </div>
        </section>

        <!-- Program Highlights -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-center text-slate-800 mb-12">Program Highlights</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="item in highlights" :key="item.title" class="bg-white p-8 rounded-xl shadow-sm">
                        <h3 class="font-serif text-2xl font-bold text-slate-800 mb-6">{{ item.title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Families Love It -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-center text-slate-800 mb-12">Why Families Love It</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="item in reasons" :key="item.title" class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center relative">
                                <span class="absolute w-2 h-2 rounded-full bg-accent-500 animate-ping opacity-75"></span>
                                <span class="relative w-2 h-2 rounded-full bg-accent-500"></span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 mb-2">{{ item.title }}</h3>
                            <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-24 bg-brand-500 text-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-3xl sm:text-4xl font-serif font-bold mb-4">Ready to Join?</h3>
                    <p class="text-white/90 max-w-2xl mx-auto text-lg">
                        Complete the form below to register for the year, month, or a drop-in day!
                    </p>
                </div>
                
                <div class="text-center">
                    <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Sign Up for After School
                    </Link>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

