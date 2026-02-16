<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const generalStudies = [
    { title: 'Language Arts', description: 'Reading, writing, and communication skills through literature, journaling, and discussion.' },
    { title: 'Mathematics', description: 'Conceptual understanding and real-world applications, preparing students for higher-level problem solving.' },
    { title: 'Science & Technology', description: 'Hands-on experiments, STEM exploration, robotics, coding, and engineering design.' },
    { title: 'Arts & Music', description: 'Creativity and self-expression through visual arts, music instruction, and collaborative projects.' }
];

const judaicStudies = [
    { title: 'Hebrew Language', description: 'Students continue Hebrew acquisition through TAL AM (Technological Approach to Language and Heritage), a multi-sensory curriculum using music, games, visual aids, and texts.' },
    { title: 'Torah & Jewish History', description: 'Study of classical texts, Jewish ethics, and historical events with an emphasis on critical inquiry and discussion.' },
    { title: 'Culture & Values', description: 'Lessons on Jewish holidays, traditions, and community practices integrated with daily learning.' },
    { title: 'Electives', description: 'Older students select tracks such as Books of the People for advanced text study, or People of the Book for Jewish thought, culture, and synagogue literacy. Upper-grade electives prepare students for Bar/Bat Mitzvah and high school readiness.' }
];

const environment = [
    { title: 'Small Class Sizes', description: 'Individualized attention ensures students receive both challenge and support.' },
    { title: 'Restorative Practices', description: 'Students learn accountability, empathy, and conflict resolution in a nurturing environment.' },
    { title: 'Holistic Growth', description: 'Project-based learning, enrichment activities, and cross-disciplinary experiences promote intellectual curiosity and social-emotional development.' }
];
</script>

<template>
    <Head title="Lower School (Grades 1-4) - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section - Image only (Edit 25: updated header image) -->
        <section class="relative h-[400px] overflow-hidden">
            <img 
                src="/img/graphics/programs/lower school/websilver-lowerschool.png" 
                alt="Lower School Classroom" 
                class="w-full h-full object-cover" 
            />
        </section>
        <!-- Hero Title - Below image -->
        <section class="py-8 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight tracking-tight w-[65%] mx-auto">
                    Integrated Learning for Academic Excellence and Jewish Identity
                </h1>
            </div>
        </section>

        <!-- Overview Section - Two Column Layout -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Content -->
                    <div>
                        <p class="text-brand-500 font-semibold uppercase tracking-widest text-sm mb-4">Why Lower School at Silver</p>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight mb-6">
                            Rigorous Academics and Jewish Values
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed mb-4">
                            At The Silver Academy, Grades 1–4 combine rigorous academics with a rich Judaic curriculum, 
                            nurturing analytical thinking, ethical reasoning, and a love of learning. Our integrated approach 
                            allows students to explore general and Jewish studies together.
                        </p>
                        <p class="text-slate-600 text-lg leading-relaxed mb-8">
                            With small class sizes and individualized attention, every student is seen, supported, and challenged 
                            to grow academically, socially, and spiritually.
                        </p>

                        <!-- Feature List -->
                        <div class="space-y-6 mb-8">
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Rigorous Academics</h3>
                                <p class="text-slate-600 text-sm">Strong foundations in language arts, mathematics, science, and technology through engaging instruction.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Hebrew Language & Torah</h3>
                                <p class="text-slate-600 text-sm">Multi-sensory Hebrew learning and classical text study build Jewish literacy and identity.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Critical Thinking</h3>
                                <p class="text-slate-600 text-sm">Project-based learning and inquiry-based instruction develop analytical skills.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Individualized Support</h3>
                                <p class="text-slate-600 text-sm">Small classes ensure personalized attention for both challenge and support.</p>
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
                            src="/img/graphics/programs/lower school/web-silveracademy1-gavi crayon art.jpg" 
                            alt="Lower School students learning" 
                            class="absolute inset-0 w-full h-full object-cover rounded-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-500 shadow-xl"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- General Studies -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-slate-800 mb-12 border-b border-brand-200 pb-4">General Studies</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="item in generalStudies" :key="item.title" class="bg-white p-8 rounded-xl shadow-sm">
                        <h3 class="font-serif text-2xl font-bold text-slate-800 mb-6">{{ item.title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Judaic Studies -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-center text-slate-800 mb-12">Judaic Studies</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="item in judaicStudies" :key="item.title" class="flex gap-4">
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

        <!-- Learning Environment -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold text-slate-800 mb-12 text-center">Learning Environment</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="item in environment" :key="item.title" class="bg-white p-8 rounded-xl shadow-sm">
                        <h3 class="font-serif text-2xl font-bold text-slate-800 mb-6">{{ item.title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values & CTA -->
        <section class="py-24 bg-brand-500 text-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-4">Inclusive & Values-Driven</h2>
                    <p class="text-white/90 max-w-2xl mx-auto text-lg">
                        All students, regardless of background, are welcomed into our community and thrive through a 
                        supportive, values-based approach that emphasizes ethical behavior, curiosity, and respect for others.
                    </p>
                </div>
                
                <div class="text-center">
                    <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Learn More About Lower School Programs
                    </Link>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

