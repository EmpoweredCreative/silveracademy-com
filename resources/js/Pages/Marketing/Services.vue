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
    }
];

const gradeLevels = [
    {
        title: 'Ganeinu',
        subtitle: 'Early Childhood Program',
        description: 'Early literacy and numeracy, creative play, social-emotional development, and introduction to Jewish stories, songs, and traditions.',
        image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=800&q=80',
        link: '/programs/ganeinu'
    },
    {
        title: 'Kindergarten',
        subtitle: 'Foundational Skills',
        description: 'Foundational skills in reading, math, and science alongside art, music, and movement. Jewish values and Torah studies integrated into daily lessons.',
        image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80',
        link: '/programs/kindergarten'
    },
    {
        title: 'Lower School',
        subtitle: 'Grades 1-4',
        description: 'Strong academic foundation, project-based learning, STEAM enrichment, and Hebrew language. Focus on social-emotional learning and character building.',
        image: 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80',
        link: '/programs/lower-school'
    },
    {
        title: 'Upper School',
        subtitle: 'Grades 5-8',
        description: 'Advanced academics with critical thinking focus. Leadership opportunities, continued Judaic studies, and hands-on technology projects.',
        image: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
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
                <div class="absolute inset-0 bg-black/30"></div>
            </div>
        </section>

        <!-- Intro Section -->
        <section class="py-20 bg-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight mb-6">
                        Learning, Growing, and Thriving<br />at Every Age
                    </h1>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
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

        <!-- Interactive Programs Section -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold">Explore Our Programs</h2>
                </div>

                <!-- Grade Levels Interactive Accordion -->
                <div class="h-[500px] flex w-full gap-2" @mouseenter="isHoveringGrades = true" @mouseleave="isHoveringGrades = false">
                    <div 
                        v-for="(grade, index) in gradeLevels" 
                        :key="index"
                        class="relative rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 ease-in-out group"
                        :class="activeGrade === index ? 'flex-grow-[3]' : 'flex-grow-[1] hover:flex-grow-[1.2]'"
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
                        
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8 flex flex-col justify-end h-full z-10">
                            <div class="transform transition-all duration-300"
                                 :class="activeGrade === index ? 'translate-y-0' : 'translate-y-4'">
                                <!-- Background Bar for Title - Blue when collapsed, Yellow when expanded -->
                                <div 
                                    class="inline-block px-4 py-2 mb-2 rounded transition-colors duration-300"
                                    :class="activeGrade === index ? 'bg-accent-500/90' : 'bg-brand-500/90'"
                                >
                                    <h3 
                                        class="text-white font-bold uppercase tracking-wider transition-all duration-300 whitespace-nowrap"
                                        :class="activeGrade === index ? 'text-2xl' : 'text-sm md:text-base'"
                                    >
                                        {{ grade.title }}
                                    </h3>
                                </div>
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
                                        Learn More
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
                    <Link
                        href="/contact"
                        class="inline-block px-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-800 bg-white border-2 border-slate-300 rounded hover:bg-accent-500 hover:text-white hover:border-accent-500 transition-colors duration-200"
                    >
                        Schedule a Visit
                    </Link>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
