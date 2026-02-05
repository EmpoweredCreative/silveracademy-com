<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const activeGrade = ref(0);
const isHoveringGrades = ref(false);

const pillars = [
    { 
        title: 'Dual Excellence', 
        description: 'Fully integrated general and Judaic curriculum ensures students are academically prepared and deeply connected to Jewish values.'
    },
    { 
        title: 'Small Class Sizes', 
        description: 'Maximum 12 students per class ensures individualized attention.'
    },
    { 
        title: 'Holistic Learning', 
        description: 'Hands-on experiences in art, music, STEM, and project-based learning foster curiosity and collaboration.'
    },
    { 
        title: 'Character & Values', 
        description: 'Restorative practices cultivate empathy, integrity, and responsibility.'
    },
    { 
        title: 'Jewish Identity & Pride', 
        description: 'Daily engagement with Torah, tradition, and Jewish holidays builds confidence and connection.'
    },
    { 
        title: 'Accessible Education', 
        description: 'We are committed to making a Silver Academy education reachable for every family through generous scholarship programs and tuition assistance.'
    }
];

const gradeLevels = [
    {
        title: 'Ganeinu',
        subtitle: 'Preschool',
        description: 'Our early childhood program fosters creativity, socialization, and a love for Jewish traditions in a warm, nurturing environment.',
        image: '/img/graphics/home/web-silveracademyGaneinu.jpg',
        link: '/programs/ganeinu'
    },
    {
        title: 'Kindergarten',
        subtitle: 'Foundations',
        description: 'Building strong foundations in literacy, numeracy, and Hebrew language through play-based and structured learning.',
        image: '/img/graphics/home/web-silveracademyKindergarten.jpg',
        link: '/programs/kindergarten'
    },
    {
        title: 'Lower School',
        subtitle: 'Grades 1-4',
        description: 'Developing critical thinking skills and independence while deepening connection to Jewish identity and community.',
        image: '/img/graphics/home/web-silveracademyLower School2.jpg',
        link: '/programs/lower-school'
    },
    {
        title: 'Upper School',
        subtitle: 'Grades 5-8',
        description: 'Preparing students for high school and beyond with rigorous academics, leadership opportunities, and ethical growth.',
        image: '/img/graphics/home/web-silveracademyUpper School 2.jpg',
        link: '/programs/upper-school'
    }
];

const enrichment = [
    { title: 'ADAM Intelligence', description: 'Developing essential human capacities: resilience, empathy, critical thinking, and teamwork.' },
    { title: 'Music & Arts', description: 'Choir, instrumental instruction, visual arts, and performances.' },
    { title: 'STEM & Robotics', description: 'Coding, engineering design, and hands-on experiments.' },
    { title: 'Environmental Sustainability', description: 'Jewish Nature Studies, outdoor learning, and ecological awareness.' },
    { title: 'Intergenerational Learning', description: '“Better Together” program connecting students with senior community members.' },
    { title: 'Athletics & Wellness', description: 'Gym partnerships with JCC & YMCA; team sports, health, and wellness activities.' },
];
</script>

<template>
    <Head title="Programs - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80" 
                    alt="Students learning" 
                    class="w-full h-full object-cover opacity-100" 
                />
            </div>
        </section>

        <!-- Intro Section -->
        <section class="py-20 bg-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-none tracking-tight mb-6">
                        Learning, Growing, and Thriving<br />at Every Age
                    </h1>
                    <p class="text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        From Pre-K through 8th grade, The Silver Academy offers a rich, values-driven curriculum 
                        that nurtures the whole child – academically, socially, and spiritually.
                    </p>
                </div>
            </div>
        </section>

        <!-- Our Approach & Pillars -->
        <section id="our-approach" class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl text-slate-800 font-bold mb-4">Our Approach</h2>
                    <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                        At The Silver Academy, learning goes beyond academics. We integrate general studies, 
                        Judaic learning, and character development into every classroom experience, 
                        helping students develop critical thinking, creativity, and confidence.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(pillar, index) in pillars" :key="pillar.title" class="relative bg-white p-8 rounded-xl shadow-sm border-t-4 border-brand-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                        <!-- Logo Watermark -->
                        <img 
                            src="/img/icons/safavicon.png" 
                            alt=""
                            class="absolute -right-6 -bottom-6 w-40 h-40 opacity-5 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none select-none z-0 grayscale"
                        />

                        <div class="relative z-10">
                            <h3 class="font-serif font-bold text-brand-700 text-2xl mb-3 group-hover:text-brand-600 transition-colors duration-300">{{ pillar.title }}</h3>
                            <p class="text-slate-600 leading-relaxed">{{ pillar.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Grade Levels Interactive Accordion -->
        <section class="py-8 bg-slate-100">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Grade levels: vertical on mobile, horizontal on md+ -->
                <div class="flex flex-col md:flex-row w-full gap-2 min-h-[920px] md:min-h-0 md:h-[500px] rounded-2xl overflow-hidden" @mouseenter="isHoveringGrades = true" @mouseleave="isHoveringGrades = false">
                    <div 
                        v-for="(grade, index) in gradeLevels" 
                        :key="index"
                        class="relative rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 ease-in-out group flex-shrink-0 md:flex-shrink md:min-h-0"
                        :class="[
                            activeGrade === index ? 'md:flex-grow-[3]' : 'md:flex-grow-[1] md:hover:flex-grow-[1.2]',
                            activeGrade === index ? 'min-h-[420px]' : 'min-h-[230px]'
                        ]"
                        @click="activeGrade = index"
                        @mouseenter="activeGrade = index"
                    >
                        <img 
                            :src="grade.image" 
                            :alt="grade.title" 
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                            :class="activeGrade === index ? 'scale-100' : 'scale-110'"
                        />
                        <div class="absolute inset-0 bg-black transition-opacity duration-300"
                             :class="isHoveringGrades ? 'opacity-70' : (activeGrade === index ? 'opacity-60' : 'opacity-50')"></div>
                        
                        <!-- Content: extra top padding on mobile only when this panel is expanded -->
                        <div 
                            class="absolute bottom-0 left-0 right-0 p-4 pt-6 md:p-8 flex flex-col justify-end h-full z-10"
                            :class="activeGrade === index ? 'pt-12 md:pt-8' : ''"
                        >
                            <div class="transform transition-all duration-300"
                                 :class="activeGrade === index ? 'translate-y-0' : 'translate-y-4'">
                                <!-- Background Bar for Title - Blue when collapsed, Yellow when expanded -->
                                <Link 
                                    :href="grade.link"
                                    class="inline-block px-4 py-2 mb-2 rounded transition-colors duration-300 hover:opacity-90 cursor-pointer"
                                    :class="activeGrade === index ? 'bg-accent-500/90' : 'bg-brand-500/90'"
                                >
                                    <h3 
                                        class="text-white font-bold uppercase tracking-wider transition-all duration-300 whitespace-nowrap"
                                        :class="activeGrade === index ? 'text-2xl' : 'text-sm md:text-base'"
                                    >
                                        {{ grade.title }}
                                    </h3>
                                </Link>
                                <p 
                                    class="text-white/90 text-xs md:text-sm font-medium uppercase tracking-widest mb-4 transition-opacity duration-300"
                                    :class="activeGrade === index ? 'opacity-100' : 'opacity-100 md:opacity-0'"
                                >
                                    {{ grade.subtitle }}
                                </p>
                                <div 
                                    class="overflow-hidden transition-all duration-500"
                                    :class="activeGrade === index ? 'max-h-40 opacity-100' : 'max-h-0 opacity-0'"
                                >
                                    <p class="text-white/90 mb-6 leading-relaxed text-sm md:text-base">
                                        {{ grade.description }}
                                    </p>
                                    <Link 
                                        :href="grade.link"
                                        class="inline-flex items-center text-white font-semibold text-sm uppercase tracking-wider hover:text-accent-300 transition-colors"
                                    >
                                        Discover More
                                        <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Special Programs & Enrichment -->
        <section class="py-24 bg-brand-500 text-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-4">Special Programs & Enrichment</h2>
                    <p class="text-white/90 max-w-2xl mx-auto">
                        We develop essential human capacities for a rapidly changing world through diverse enrichment opportunities.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="program in enrichment" :key="program.title" class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                        <h3 class="text-xl font-bold text-brand-600 mb-3">{{ program.title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ program.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Life at Silver Academy -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="bg-slate-50 rounded-3xl p-8 md:p-16 text-center">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold mb-6">Life at Silver Academy</h2>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-3xl mx-auto mb-8">
                        Our students experience a warm, supportive culture where collaboration, respect, and curiosity are celebrated. 
                        Daily interactions, enrichment programs, and school-wide events help students thrive academically, 
                        socially, and spiritually while exploring passions and developing meaningful friendships.
                    </p>
                    <a
                        href="https://calendar.app.google/Y5NrAjA9RooWgwZ98"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-block px-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-800 bg-white border-2 border-slate-300 rounded hover:bg-accent-500 hover:text-white hover:border-accent-500 transition-colors duration-200"
                    >
                        Schedule a Visit
                    </a>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
