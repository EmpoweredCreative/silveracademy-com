<script setup>
import { Head } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const reasons = [
    { title: 'Community Connections', description: 'Meet other Silver Academy families, form friendships, and build lasting relationships.' },
    { title: 'Family Engagement', description: 'Participate in school events, volunteer opportunities, and social gatherings.' },
    { title: 'Supportive Network', description: 'Share experiences, resources, and advice with other families navigating the school journey.' },
    { title: 'Celebrate Together', description: 'Enjoy programs and celebrations that honor our shared values, traditions, and achievements.' },
];
</script>

<template>
    <Head title="Silver Parent Circle - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1920&q=80" 
                    alt="Families Connecting" 
                    class="w-full h-full object-cover" 
                />
                <div class="absolute inset-0 bg-brand-900/60 mix-blend-multiply"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center px-4">
                <div class="max-w-4xl">
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-white leading-tight tracking-tight mb-6">
                        Connect, Collaborate, and Celebrate as a Silver Family
                    </h1>
                </div>
            </div>
        </section>

        <!-- Overview -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-xl text-slate-600 leading-relaxed mb-6">
                        Building a strong, supportive community for families at The Silver Academy.
                    </p>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        At The Silver Academy, we are more than a school, we are a family. The Silver Parent Circle is a wonderful way for 
                        parents and guardians to connect, collaborate, and engage with other families, fostering a sense of 
                        belonging and partnership in our school community.
                    </p>
                </div>
            </div>
        </section>

        <!-- Why Join -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-center text-slate-800 mb-12">Why Join the Parent Circle?</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="item in reasons" :key="item.title" class="bg-white p-8 rounded-xl shadow-sm">
                        <h3 class="font-serif text-2xl font-bold text-slate-800 mb-6">{{ item.title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Who Can Join -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl font-bold text-slate-800 mb-8">Who Can Join?</h2>
                <div class="bg-brand-50 rounded-2xl p-8 md:p-12">
                    <p class="text-xl text-slate-600 leading-relaxed">
                        The Parent Circle welcomes <span class="font-bold text-brand-600">all families</span>, whether you are new to The Silver Academy or have been part of our community for years.
                    </p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-24 bg-brand-500 text-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-3xl sm:text-4xl font-serif font-bold mb-4">Get Involved Today</h3>
                    <p class="text-white/90 max-w-2xl mx-auto text-lg">
                        For more information or to get involved, contact us at <a href="mailto:parentcircle@silveracademypa.org" class="text-accent-300 hover:text-accent-200 underline">parentcircle@silveracademypa.org</a>.
                    </p>
                </div>
                
                <div class="text-center">
                    <a href="mailto:parentcircle@silveracademypa.org" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Join the Parent Circle
                    </a>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

