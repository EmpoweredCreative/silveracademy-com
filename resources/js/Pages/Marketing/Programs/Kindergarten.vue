<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const generalStudies = [
    { title: 'General Studies', description: 'Kindergarten students develop essential skills in writing, reading, mathematics, and reasoning, while building strong social and communication abilities. Our teachers guide students toward accelerated achievement in both academics and social growth, laying the foundation for success in school and beyond.' },
    { title: 'Arts & Music', description: 'Our Arts program nurtures creativity, self-expression, and innovative thinking. Students receive individualized and collaborative instruction in Music, and regularly showcase their skills through school-wide programs and performances.' },
    { title: 'Science & Technology', description: 'Students explore the natural world through hands-on scientific inquiry and observation. Our technology program integrates interactive projects and computer-based learning, equipping children with skills in research, public speaking, and problem-solving.' }
];

const judaicStudies = [
    { title: 'Language', description: 'Kindergarteners begin Hebrew language acquisition through immersion, learning everyday words and phrases. This early language exposure supports critical thinking, communication, and cognitive development.' },
    { title: 'Values', description: 'Students practice social responsibility, empathy, and community engagement, learning how to be active, caring members of their school community.' },
    { title: 'Tradition', description: 'Children explore Jewish traditions and customs through interactive lessons and special projects, encouraging them to carry these lessons into daily life and family routines.' }
];
</script>

<template>
    <Head title="Kindergarten - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="/img/graphics/programs/kindergarten/web-silveracademy2025_Silver Academy-831.jpg" 
                    alt="Kindergarten Classroom" 
                    class="w-full h-full object-cover"
                    style="object-position: center 35%;"
                />
                <div class="absolute inset-0 bg-brand-900/60 mix-blend-multiply"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center px-4">
                <div class="max-w-4xl">
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-white leading-tight tracking-tight mb-6">
                        Exploration, Discovery, and a Strong Foundation for Lifelong Learning
                    </h1>
                </div>
            </div>
        </section>

        <!-- Overview Section - Two Column Layout -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Content -->
                    <div>
                        <p class="text-brand-500 font-semibold uppercase tracking-widest text-sm mb-4">Why Kindergarten at Silver</p>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight mb-6">
                            Building Strong Foundations for Lifelong Learning
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed mb-4">
                            Our full-day Kindergarten program gives young learners the opportunity to reach their full potential 
                            through curiosity, creativity, and collaboration. Students engage in a rigorous, values-driven curriculum 
                            that balances academic achievement with Jewish identity.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            Each child receives individualized attention, fostering confidence, independence, and a love of learning 
                            that prepares them for success in school and beyond.
                        </p>

                        <!-- Feature List -->
                        <div class="space-y-6 mb-8">
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Rigorous Academics</h3>
                                <p class="text-slate-600 text-sm">Strong foundations in reading, writing, and mathematics through engaging, hands-on instruction.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Integrated Jewish Learning</h3>
                                <p class="text-slate-600 text-sm">Hebrew language, values, and traditions woven into daily experiences.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Social-Emotional Development</h3>
                                <p class="text-slate-600 text-sm">Building empathy, responsibility, and collaboration through restorative practices.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Individualized Attention</h3>
                                <p class="text-slate-600 text-sm">Small class sizes ensure every child is seen, supported, and challenged.</p>
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
                            src="/img/graphics/home/web-silveracademyKindergarten.jpg" 
                            alt="Kindergarten students learning" 
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
                <div class="grid md:grid-cols-3 gap-8">
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
                <div class="grid md:grid-cols-3 gap-8">
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

        <!-- Inclusivity & CTA -->
        <section class="py-24 bg-brand-500 text-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-4">Inclusivity</h2>
                    <p class="text-white/90 max-w-2xl mx-auto text-lg">
                        Kindergarten at The Silver Academy is open to all students, regardless of background, 
                        fostering a welcoming and diverse learning community.
                    </p>
                </div>
                
                <div class="text-center">
                    <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Learn More About Kindergarten
                    </Link>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

