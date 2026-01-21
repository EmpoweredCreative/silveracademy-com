<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const values = [
    { title: 'Jewish Identity', icon: '/img/icons/Jewish Identity.png' },
    { title: 'Community Responsibility', icon: '/img/icons/Community.png' },
    { title: 'Compassionate Empathy', icon: '/img/icons/Empathy.png' },
    { title: 'Intellectual Curiosity', icon: '/img/icons/Intellectual.png' },
    { title: 'Unwavering Integrity', icon: '/img/icons/integrity.png' },
    { title: 'Intentional Action', icon: '/img/icons/Action.png' },
];

const cards = [
    {
        title: 'WHY THE SILVER ACADEMY',
        image: '/img/graphics/about/web-silveracademyWhy Silver.jpg',
        link: '/why-silver-academy'
    },
    {
        title: 'MEET OUR AMAZING TEAM',
        image: '/img/graphics/about/web-silveracademyAmazing Team.jpg',
        link: '#'
    },
    {
        title: 'LEARN ABOUT OUR COMMUNITY',
        image: '/img/graphics/about/web-silveracademyCommunity 2.jpg',
        link: '/our-community'
    }
];
</script>

<template>
    <Head title="About Us - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Image -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="/img/graphics/about/web-silveracademyCommunity.jpg" 
                    alt="Students smiling" 
                    class="w-full h-full object-cover object-center"
                />
                <div class="absolute inset-0 bg-black/20"></div>
            </div>
        </section>

        <!-- Intro Section -->
        <section class="py-20 bg-white text-center">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight">
                    A Tradition of Empowering<br />
                    Learners and Leaders
                </h1>
                <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                    For more than 80 years, The Silver Academy has helped students grow academically, 
                    socially, and spiritually, nurturing confidence, curiosity, and character.
                </p>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="pb-8 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Mission Card -->
                    <div class="relative h-[400px] rounded-lg overflow-hidden group">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img 
                                src="/img/graphics/mission-vision/web-silveracademyIMG_3010.jpg" 
                                alt="School Building" 
                                class="w-full h-full object-cover"
                            />
                            <!-- Blue Overlay -->
                            <div class="absolute inset-0 bg-brand-900/90 mix-blend-multiply"></div>
                        </div>
                        
                        <!-- Content -->
                        <div class="absolute inset-0 p-8 flex flex-col items-center justify-center text-center">
                            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-6 uppercase tracking-wide">
                                Our Mission
                            </h2>
                            <p class="text-white/90 text-lg leading-relaxed max-w-md">
                                The Silver Academy empowers Jewish students from Pre-K to 8th grade 
                                through rigorous general and Judaic studies, nurturing character, critical 
                                thinking, and a lifelong love of learning.
                            </p>
                        </div>
                        
                        <!-- Yellow Bottom Border -->
                        <div class="absolute bottom-0 left-0 right-0 h-2 bg-accent-500"></div>
                    </div>

                    <!-- Vision Card -->
                    <div class="relative h-[400px] rounded-lg overflow-hidden group">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img 
                                src="/img/graphics/mission-vision/web-silveracademyIMG_3013.jpg" 
                                alt="School Building" 
                                class="w-full h-full object-cover"
                            />
                            <!-- Blue Overlay -->
                            <div class="absolute inset-0 bg-brand-900/90 mix-blend-multiply"></div>
                        </div>
                        
                        <!-- Content -->
                        <div class="absolute inset-0 p-8 flex flex-col items-center justify-center text-center">
                            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-6 uppercase tracking-wide">
                                Our Vision
                            </h2>
                            <p class="text-white/90 text-lg leading-relaxed max-w-md">
                                We envision generations of students who grow with confidence, character, 
                                and a deep love of Jewish learning—equipped to lead lives of purpose, 
                                compassion, and faith.
                            </p>
                        </div>
                        
                        <!-- Yellow Bottom Border -->
                        <div class="absolute bottom-0 left-0 right-0 h-2 bg-accent-500"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="pt-8 pb-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <img src="/img/icons/TSA Logo-inverted.svg" alt="Star of David" class="w-[116px] h-[116px] text-brand-500" />
                    </div>
                    
                    <h2 class="font-serif text-4xl text-slate-700 tracking-wide uppercase">
                        Our Values
                    </h2>
                </div>

                <div class="grid md:grid-cols-3 gap-x-8 gap-y-16 max-w-5xl mx-auto">
                    <div 
                        v-for="value in values" 
                        :key="value.title"
                        class="flex flex-col items-center text-center"
                    >
                        <!-- Icon Image -->
                        <div class="w-20 h-20 mb-6 flex items-center justify-center">
                            <img 
                                :src="value.icon" 
                                :alt="value.title"
                                class="w-full h-full object-contain"
                            />
                        </div>
                        
                        <h3 class="text-xl font-semibold text-slate-700 leading-tight max-w-[200px]">
                            {{ value.title }}
                        </h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Story Section -->
        <section class="bg-slate-50 py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Large Image -->
                <div class="relative w-full h-[500px] rounded-t-lg overflow-hidden mb-0">
                    <img 
                        src="/img/graphics/about/web-silveracademyHistory.jpg" 
                        alt="The Silver Academy Building" 
                        class="w-full h-full object-cover"
                    />
                </div>
                
                <!-- Story Content -->
                <div class="bg-slate-50 text-center max-w-4xl mx-auto pt-16 pb-8">
                    <h2 class="font-serif text-4xl text-slate-700 tracking-wide uppercase mb-4">
                        Our Story
                    </h2>
                    <h3 class="font-serif text-2xl text-slate-600 mb-8 italic">
                        A Legacy of Learning, Leadership, and Jewish Life
                    </h3>
                    <p class="text-lg text-slate-600 leading-relaxed mb-12">
                        Founded in 1944 by Rabbi David L. Silver (z"l), The Silver Academy was built on a vision of academic 
                        excellence, community responsibility, and vibrant Jewish living. From the first graduating class in 
                        1950 to today, the school has nurtured generations of students, equipping them with knowledge, 
                        values, and a strong sense of Jewish identity. Through campus expansions, innovative programming, 
                        and a commitment to evolving educational practices, The Silver Academy continues to prepare 
                        students to thrive in a complex world, guided by the principles and vision of its founder.
                    </p>
                    
                    <a 
                        href="#" 
                        class="inline-block px-8 py-3 text-sm font-semibold uppercase tracking-wider text-brand-600 bg-white border-2 border-accent-500 rounded hover:bg-accent-50 transition-colors duration-200"
                    >
                        Visit Our Campus
                    </a>
                </div>
            </div>
        </section>

        <!-- Three Cards Section -->
        <section id="why-silver-academy" class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8 mb-24">
                    <Link
                        v-for="card in cards" 
                        :key="card.title"
                        :href="card.link"
                        class="relative aspect-[3/4] group overflow-hidden cursor-pointer block"
                    >
                        <img 
                            :src="card.image" 
                            :alt="card.title" 
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />
                        <div class="absolute bottom-0 left-0 right-0 bg-brand-500/90 py-4 px-6">
                            <h3 class="text-white text-center font-bold uppercase tracking-wider text-sm">
                                {{ card.title }}
                            </h3>
                        </div>
                    </Link>
                </div>

                <!-- Final Group Photo -->
                <div class="w-full h-[600px] overflow-hidden">
                    <img 
                        src="/img/graphics/about/web-silveracademyFooter.jpg" 
                        alt="Silver Academy Community" 
                        class="w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
