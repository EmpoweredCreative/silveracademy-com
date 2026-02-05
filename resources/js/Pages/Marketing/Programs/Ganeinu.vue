<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const curriculum = [
    { title: 'Alef-Bet', description: 'Each Tuesday we introduce a new Hebrew letter, learning its sound, exploring Hebrew words, and identifying it in prayers and songs.' },
    { title: 'Parsha (Bible Stories)', description: 'Thursdays are dedicated to the weekly Parsha. Students act out stories, create crafts, and begin to explore the narratives of their heritage.' },
    { title: 'Hebrew Language & Unit Projects', description: 'Children learn Hebrew vocabulary through themed units, including colors, body parts, directions, and more.' },
    { title: 'Jewish Holidays', description: 'Students experience the traditions, foods, prayers, and songs associated with each holiday throughout the year.' },
    { title: 'Circle Time & Tzedakah', description: 'Daily circle time encourages sharing mitzvot and contributing to a tzedakah box, teaching the value of giving.' },
    { title: 'Song & Prayer (Shira V’T’fillah)', description: 'Students learn simple, meaningful songs and prayers that form the foundation of all branches of Judaism.' },
    { title: 'Snack & Exploration', description: 'Snack time doubles as a curiosity-driven activity. Children explore science and technology concepts, such as watching popcorn pop in slow motion or learning how foods are made.' },
    { title: 'Shabbat Party', description: 'Thursdays feature a Shabbat celebration with candle lighting, blessings, singing V’shamru, Kiddush over grape juice, and Motzi over challah—providing early exposure to Judaic rituals in a joyful, hands-on way.' },
];

const outcomes = [
    'Develop a love of learning and Jewish identity',
    'Begin foundational Hebrew and Torah skills',
    'Build social, emotional, and ethical awareness',
    'Participate in a joyful, values-driven classroom community'
];
</script>

<template>
    <Head title="Ganeinu (Early Childhood) - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section - Image only -->
        <section class="relative h-[400px] overflow-hidden">
            <img 
                src="/img/graphics/programs/ganeinu/web-silveracademyIMG_6289%202.jpg" 
                alt="Ganeinu Early Childhood" 
                class="w-full h-full object-cover"
                style="object-position: center 30%;"
            />
        </section>
        <!-- Hero Title - Below image -->
        <section class="py-8 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight">
                    Shalom! Welcome to Ganeinu
                </h1>
            </div>
        </section>

        <!-- Overview Section - Two Column Layout -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Content -->
                    <div>
                        <p class="text-brand-500 font-semibold uppercase tracking-widest text-sm mb-4">Why Ganeinu</p>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight mb-6">
                            A Joyful Start to Jewish Learning
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed mb-4">
                            Ganeinu is our early childhood program designed for our youngest learners to cultivate curiosity, 
                            confidence, and a love of learning. Students engage in hands-on experiences that build foundational 
                            academic, social, and emotional skills.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            Children explore Jewish heritage, traditions, and values in a warm, nurturing environment—gaining 
                            the tools they need to thrive in school and beyond.
                        </p>

                        <!-- Feature List -->
                        <div class="space-y-6 mb-8">
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Early Literacy & Language</h3>
                                <p class="text-slate-600 text-sm">Hebrew letters, vocabulary, and foundational reading skills introduced through play and song.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Jewish Identity & Values</h3>
                                <p class="text-slate-600 text-sm">Weekly Parsha stories, holiday celebrations, and daily rituals build connection to heritage.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Social-Emotional Growth</h3>
                                <p class="text-slate-600 text-sm">Circle time, tzedakah, and collaborative activities foster empathy and community.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Hands-On Exploration</h3>
                                <p class="text-slate-600 text-sm">Curiosity-driven learning through science, art, music, and creative play.</p>
                            </div>
                        </div>

                        <a 
                            href="https://calendar.app.google/Y5NrAjA9RooWgwZ98"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block rounded bg-accent-500 px-8 py-4 text-sm font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300"
                        >
                            Schedule a Visit
                        </a>
                    </div>

                    <!-- Right Image with Decorative Background -->
                    <div class="relative h-full min-h-[500px]">
                        <!-- Decorative angled background -->
                        <div class="absolute inset-0 bg-brand-500 rounded-2xl transform rotate-3"></div>
                        <!-- Main image -->
                        <img 
                            src="/img/graphics/programs/ganeinu/web-silveracademyIMG_6136.jpg" 
                            alt="Ganeinu students learning" 
                            class="absolute inset-0 w-full h-full object-cover rounded-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-500 shadow-xl"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Schedule & Drop-Off -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-start">
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <h2 class="font-serif text-2xl font-bold text-slate-800 mb-6">Schedule & Drop-Off</h2>
                        <div class="space-y-4 text-slate-600">
                            <p><strong>Ganeinu meets Tuesday and Thursday afternoons from 12:00–3:30 pm.</strong></p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Children attending the JCC Early Learning Center are picked up from their class before lunch.</li>
                                <li>Students arriving from outside may be dropped off at the Ganeinu room.</li>
                                <li>Each session begins with a small opening activity (12:00–12:15 pm) before lunch to help students settle in and connect.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <h2 class="font-serif text-2xl font-bold text-slate-800 mb-6">Lunch</h2>
                        <div class="space-y-4 text-slate-600">
                            <p>
                                Students eat together in the Silver Academy lunchroom at 12:15 pm, sharing the space with lower grades and middle schoolers. 
                                After lunch, they recite the first paragraph of Bircat Hamazon (Grace after Meals), and by January, most students can sing 
                                along with this foundational t’fillah (prayer).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum Highlights -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-center text-slate-800 mb-12">Curriculum Highlights</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="item in curriculum" :key="item.title" class="flex gap-4">
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

        <!-- Learning Outcomes & CTA -->
        <section class="py-24 bg-brand-500 text-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-4">Learning Outcomes</h2>
                    <p class="text-white/90 max-w-2xl mx-auto text-lg">
                    Ganeinu offers a taste of the Silver Academy’s Judaic education at the early childhood level, helping children:
                </p>
                </div>
                <div class="grid sm:grid-cols-2 gap-8 mb-16">
                    <div v-for="outcome in outcomes" :key="outcome" class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-pointer text-left">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-brand-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-slate-700 text-lg">{{ outcome }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Learn more about Ganeinu
                    </Link>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

