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
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1920&q=80" 
                    alt="Lower School Classroom" 
                    class="w-full h-full object-cover" 
                />
                <div class="absolute inset-0 bg-brand-900/60 mix-blend-multiply"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center px-4">
                <div class="max-w-4xl">
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-white leading-tight tracking-tight mb-6">
                        Integrated Learning for Academic Excellence and Jewish Identity
                    </h1>
                </div>
            </div>
        </section>

        <!-- Overview -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-xl text-slate-600 leading-relaxed">
                        At The Silver Academy, Grades 1–4 combine rigorous academics with a rich Judaic curriculum, 
                        nurturing analytical thinking, ethical reasoning, and a love of learning. Our integrated approach 
                        allows students to explore general and Jewish studies together, seeing how values, culture, and 
                        scholarship intersect. With small class sizes and individualized attention, every student is seen, 
                        supported, and challenged to grow academically, socially, and spiritually.
                    </p>
                </div>
            </div>
        </section>

        <!-- General Studies -->
        <section class="py-20 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-12">
                    <h2 class="font-serif text-3xl font-bold text-slate-800 mb-4">General Studies</h2>
                    <p class="text-lg text-slate-600">Our Elementary program emphasizes literacy, critical thinking, and problem-solving across subjects including:</p>
                </div>
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
        <section class="py-20 bg-brand-900 text-white text-center">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl font-bold mb-8">Inclusive & Values-Driven</h2>
                <p class="text-xl text-brand-100 mb-12">
                    All students, regardless of background, are welcomed into our community and thrive through a 
                    supportive, values-based approach that emphasizes ethical behavior, curiosity, and respect for others.
                </p>
                
                <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                    Learn More About Lower School Programs
                </Link>
            </div>
        </section>

    </MarketingLayout>
</template>

