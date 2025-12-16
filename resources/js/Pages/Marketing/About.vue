<script setup>
import { Head } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const values = [
    { title: 'Jewish Identity', icon: '✡️' },
    { title: 'Community Responsibility', icon: '🤝' },
    { title: 'Compassionate Empathy', icon: '❤️' },
    { title: 'Intellectual Curiosity', icon: '💡' },
    { title: 'Unwavering Integrity', icon: '⚖️' },
    { title: 'Intentional Action', icon: '🎯' },
];

const cards = [
    {
        title: 'WHY THE SILVER ACADEMY',
        image: 'https://images.unsplash.com/photo-1577896335477-28d5d43603c4?auto=format&fit=crop&w=800&q=80',
        link: '#'
    },
    {
        title: 'MEET OUR AMAZING TEAM',
        image: 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&w=800&q=80',
        link: '#'
    },
    {
        title: 'LEARN ABOUT OUR COMMUNITY',
        image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=800&q=80',
        link: '#'
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
                    src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1920&q=80" 
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
        <section class="pb-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Mission Card -->
                    <div class="relative h-[400px] rounded-lg overflow-hidden group">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img 
                                src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=800&q=80" 
                                alt="School Building" 
                                class="w-full h-full object-cover"
                            />
                            <!-- Blue Overlay -->
                            <div class="absolute inset-0 bg-brand-500/80 mix-blend-multiply"></div>
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
                                src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=800&q=80" 
                                alt="School Building" 
                                class="w-full h-full object-cover"
                            />
                            <!-- Blue Overlay -->
                            <div class="absolute inset-0 bg-brand-500/80 mix-blend-multiply"></div>
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
        <section class="py-24 bg-white">
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
                        <!-- Icon Placeholder (Teacher/Presentation Style) -->
                        <div class="w-20 h-20 mb-6 text-brand-500">
                            <svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
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
                        src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1920&q=80" 
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
                    <div 
                        v-for="card in cards" 
                        :key="card.title"
                        class="relative aspect-[3/4] group overflow-hidden cursor-pointer"
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
                    </div>
                </div>

                <!-- Final Group Photo -->
                <div class="w-full h-[600px] overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=1920&q=80" 
                        alt="Silver Academy Community" 
                        class="w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
