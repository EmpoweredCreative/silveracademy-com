<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const generalStudies = [
    { title: 'Language Arts & Humanities', description: 'Advanced reading, writing, analysis, and critical thinking, preparing students for high school-level study.' },
    { title: 'Mathematics & STEM', description: 'Algebra, geometry, coding, robotics, engineering design, and applied problem-solving foster analytical skills.' },
    { title: 'Science & Technology', description: 'Hands-on labs, experiments, and technology projects encourage inquiry, creativity, and innovation.' },
    { title: 'Arts & Music', description: 'Students engage in visual arts, music, and performance, showcasing creativity and collaboration.' }
];

const judaicStudies = [
    { title: 'Hebrew Language', description: 'Continued TAL AM instruction with a focus on fluency, comprehension, and applied communication.' },
    { title: 'Text Study & Jewish Thought', description: 'Electives such as Books of the People and People of the Book deepen knowledge of classical texts, Jewish ethics, and modern applications.' },
    { title: 'Jewish Life & Culture', description: 'Students engage with traditions, holidays, and values in meaningful, hands-on ways, preparing them to live fully Jewish lives while respecting diverse perspectives.' },
    { title: 'Leadership & Community', description: 'Opportunities for mentorship, peer collaboration, and service projects cultivate character and responsibility.' }
];

const environment = [
    { title: 'Individualized Attention', description: 'Small class sizes allow teachers to support each student’s academic and personal growth.' },
    { title: 'Critical Thinking & Inquiry', description: 'Students are encouraged to ask questions, explore ideas, and develop reasoned opinions.' },
    { title: 'Social-Emotional Development', description: 'Restorative practices and community-building activities foster empathy, accountability, and teamwork.' },
    { title: 'Preparation for High School', description: 'Curriculum and electives are designed to ensure students are academically ready and socially confident for the next stage.' }
];
</script>

<template>
    <Head title="Upper School (Grades 5-8) - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section - Image only -->
        <section class="relative h-[400px] overflow-hidden">
            <img 
                src="/img/graphics/programs/upper school/web-silveracademy524347888_1336777831616096_6995516925783677283_n.jpg" 
                alt="Upper School Students" 
                class="w-full h-full object-cover" 
            />
        </section>
        <!-- Hero Title - Below image -->
        <section class="py-8 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight tracking-tight w-[65%] mx-auto">
                    Supporting Growth, Curiosity, and Academic Excellence
                </h1>
            </div>
        </section>

        <!-- Overview Section - Two Column Layout -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Content -->
                    <div>
                        <p class="text-brand-500 font-semibold uppercase tracking-widest text-sm mb-4">Why Upper School at Silver</p>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-slate-800 leading-tight mb-6">
                            Preparing Students for High School and Beyond
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed mb-4">
                            Our Grades 5–8 program at The Silver Academy challenges students academically, socially, and spiritually, 
                            equipping them with the skills, confidence, and ethical grounding to succeed in high school and beyond.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            Middle School students engage in rigorous coursework while exploring leadership, Jewish identity, and 
                            real-world applications of learning.
                        </p>

                        <!-- Feature List -->
                        <div class="space-y-6 mb-8">
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Advanced Academics</h3>
                                <p class="text-slate-600 text-sm">Rigorous coursework in language arts, mathematics, STEM, and humanities prepares students for high school.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Jewish Leadership</h3>
                                <p class="text-slate-600 text-sm">Elective tracks and advanced text study develop deep Jewish knowledge and identity.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Critical Thinking & Inquiry</h3>
                                <p class="text-slate-600 text-sm">Students develop reasoned opinions and explore complex ideas through discussion and analysis.</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">High School Readiness</h3>
                                <p class="text-slate-600 text-sm">Curriculum and electives ensure students are academically prepared and socially confident.</p>
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
                            src="/img/graphics/programs/upper school/web-silveracademy2025_Silver Academy-934.jpg" 
                            alt="Upper School students learning" 
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
                <div class="text-center mb-12">
                    <h2 class="font-serif text-3xl font-bold text-slate-800 mb-4">Learning Environment</h2>
                    <p class="text-lg text-slate-600">Middle Schoolers thrive in a supportive, challenging, and flexible environment that encourages:</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="item in environment" :key="item.title" class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="font-serif text-xl font-bold text-slate-800 mb-4">{{ item.title }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ item.description }}</p>
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
                        The Silver Academy Middle School program is open to students from all backgrounds, providing a strong 
                        foundation in Jewish values while emphasizing ethical behavior, curiosity, and collaboration. Our students 
                        graduate with confidence, pride in their identity, and the tools to thrive in any environment.
                    </p>
                </div>
                
                <div class="text-center">
                    <Link href="/contact" class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        Learn More About Upper School Programs
                    </Link>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

