<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const activePanel = ref(5); // Default to Family Partnership (index 5)
const activeGrade = ref(0); // Default to Ganeinu (index 0)
const isHoveringGrades = ref(false);

const panels = [
    {
        title: 'Small Class Sizes',
        description: 'Our low student-to-teacher ratio ensures that every child is seen, heard, and valued. Teachers tailor instruction to meet individual needs, fostering deep understanding and academic confidence.',
        image: 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&w=800&q=80',
        link: '/about#why-silver-academy'
    },
    {
        title: 'Dual Curriculum',
        description: 'We seamlessly integrate rigorous general studies with rich Jewish education, empowering students to navigate the world with intellectual depth and spiritual grounding.',
        image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80',
        link: '/services#our-approach'
    },
    {
        title: 'Restorative Culture',
        description: 'We prioritize social-emotional learning and character development, building a community rooted in empathy, respect, and mutual responsibility.',
        image: 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=800&q=80',
        link: '/about#why-silver-academy'
    },
    {
        title: 'Joyful Learning',
        description: 'Education should be exciting. We create a dynamic environment where curiosity thrives, questions are encouraged, and learning is a daily adventure.',
        image: 'https://images.unsplash.com/photo-1488998427799-e3362cec87c3?auto=format&fit=crop&w=800&q=80',
        link: '/services'
    },
    {
        title: 'Accessible Education',
        description: 'We are committed to making a Silver Academy education reachable for every family through generous scholarship programs and tuition assistance.',
        image: 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=800&q=80',
        link: '/admissions#tuition'
    },
    {
        title: 'Family Partnership',
        description: 'Transparent communication and shared decision-making create a welcoming, collaborative community where parents are true partners in their child’s journey.',
        image: 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=800&q=80',
        link: '/get-involved'
    }
];

const gradeLevels = [
    {
        title: 'Ganeinu',
        subtitle: 'Preschool',
        description: 'Our early childhood program fosters creativity, socialization, and a love for Jewish traditions in a warm, nurturing environment.',
        image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=800&q=80',
        link: '/programs/ganeinu'
    },
    {
        title: 'Kindergarten',
        subtitle: 'Foundations',
        description: 'Building strong foundations in literacy, numeracy, and Hebrew language through play-based and structured learning.',
        image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80',
        link: '/programs/kindergarten'
    },
    {
        title: 'Lower School',
        subtitle: 'Grades 1-4',
        description: 'Developing critical thinking skills and independence while deepening connection to Jewish identity and community.',
        image: 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80',
        link: '/programs/lower-school'
    },
    {
        title: 'Upper School',
        subtitle: 'Grades 5-8',
        description: 'Preparing students for high school and beyond with rigorous academics, leadership opportunities, and ethical growth.',
        image: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
        link: '/programs/upper-school'
    }
];

const features = [
    {
        title: 'Rigorous Academics',
        description: 'Our curriculum challenges students to think critically while building a strong foundation in core subjects.',
        icon: '📚',
    },
    {
        title: 'Jewish Values',
        description: 'We integrate Jewish learning and traditions throughout every aspect of the school experience.',
        icon: '✡️',
    },
    {
        title: 'Small Class Sizes',
        description: 'Individual attention ensures every student receives personalized support and guidance.',
        icon: '👨‍🏫',
    },
    {
        title: 'Character Development',
        description: 'We nurture confidence, curiosity, and character in every child.',
        icon: '🌟',
    },
];

const stats = [
    { value: '61%', label: 'of students receive a scholarship' },
    { value: '12', label: 'average class size' },
    { value: '100%', label: 'high school acceptance' },
];
</script>

<template>
    <Head title="Home - Central PA's Jewish Day School" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-slate-50">
            <!-- Hero Image Background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-white/90 z-10"></div>
                <img 
                    src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1920&q=80" 
                    alt="Students learning together" 
                    class="w-full h-full object-cover object-top opacity-30"
                />
            </div>
            
            <div class="relative z-20 mx-auto max-w-7xl px-6 py-20 sm:py-28 lg:px-8 lg:py-36">
                <div class="text-center max-w-4xl mx-auto">
                    <!-- Label -->
                    <div class="mb-6">
                        <span class="inline-block px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-accent-600 bg-accent-100 rounded-full">
                            Welcome to The Silver Academy
                        </span>
                    </div>
                    
                    <!-- Main Headline - Serif Font -->
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-600 leading-tight tracking-tight font-bold">
                        Igniting a Lifelong Love of Learning, Jewish Identity, and Purposeful Living
                    </h1>
                    
                    <!-- Subheadline -->
                    <p class="mt-8 text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        Rooted in rigorous academics and Jewish values, our small-class learning environment 
                        empowers Pre-K through 8th graders to grow with confidence, curiosity, and character 
                        here in Central Pennsylvania.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            href="/admissions"
                            class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                        >
                            Learn about Admissions
                        </Link>
                        <Link
                            href="/contact"
                            class="inline-block rounded bg-accent-500 px-8 py-4 text-base font-semibold uppercase tracking-wide text-white shadow-lg hover:bg-accent-600 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                        >
                            Schedule a Visit
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Organizational Video Section -->
        <section class="w-full bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
                <div class="aspect-video relative overflow-hidden group rounded-2xl shadow-xl">
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/30 transition-colors duration-300 cursor-pointer">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 border-2 border-white/50">
                                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                            <h3 class="text-white text-xl font-semibold tracking-wider uppercase">Watch Our Story</h3>
                            <p class="text-white/80 mt-2 font-serif text-lg">Life at Silver Academy</p>
                        </div>
                    </div>
                    <!-- Placeholder Image -->
                    <img 
                        src="https://images.unsplash.com/photo-1577896335477-28d5d43603c4?auto=format&fit=crop&w=1920&q=80" 
                        alt="Silver Academy Video Placeholder" 
                        class="w-full h-full object-cover -z-10"
                    />
                </div>
            </div>
        </section>

        <!-- Value Proposition Section -->
        <section class="py-16 bg-white text-center">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 tracking-tight mb-6">
                    A School That Sees, Supports, and Nurtures Every Child
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                    We offer an individualized approach to learning with strong academics, 
                    meaningful support, and options that keep education within reach for families.
                </p>
            </div>
        </section>

        <!-- Interactive Panels Section -->
        <section class="h-[600px] w-full flex bg-slate-900 overflow-hidden">
            <div 
                v-for="(panel, index) in panels" 
                :key="index"
                class="relative transition-all duration-500 ease-in-out border-r border-white/20 last:border-r-0 cursor-pointer overflow-hidden group"
                :class="activePanel === index ? 'flex-grow-[4]' : 'flex-grow-[1] hover:flex-grow-[1.2]'"
                @click="activePanel = index"
            >
                <!-- Background Image -->
                <div class="absolute inset-0 transition-transform duration-700 ease-out"
                     :class="activePanel === index ? 'scale-100' : 'scale-110 grayscale group-hover:grayscale-0'">
                    <img :src="panel.image" :alt="panel.title" class="w-full h-full object-cover" />
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-brand-900/40 transition-opacity duration-300"
                         :class="activePanel === index ? 'opacity-20' : 'opacity-60 group-hover:opacity-40'"></div>
                     
                     <!-- Blue overlay for inactive tabs -->
                     <div class="absolute inset-0 bg-brand-600/80 mix-blend-multiply transition-opacity duration-300"
                          :class="activePanel === index ? 'opacity-0' : 'opacity-100'"></div>
                </div>

                <!-- Vertical Title (Inactive State) -->
                <div 
                    class="absolute inset-0 flex items-center justify-center pointer-events-none transition-opacity duration-300"
                    :class="activePanel === index ? 'opacity-0' : 'opacity-100'"
                >
                    <h3 class="text-white font-bold text-xl uppercase tracking-widest whitespace-nowrap rotate-180 writing-vertical-lr select-none drop-shadow-md">
                        {{ panel.title }}
                    </h3>
                </div>

                <!-- Content (Active State) -->
                <div 
                    class="absolute bottom-0 left-0 right-0 p-8 md:p-12 transition-all duration-500 transform"
                    :class="activePanel === index ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                >
                    <div class="bg-white/95 backdrop-blur-sm p-8 rounded-lg shadow-xl max-w-xl mx-auto md:mx-0">
                        <h3 class="text-2xl font-serif font-bold text-slate-800 mb-4">{{ panel.title }}</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">{{ panel.description }}</p>
                        <Link 
                            :href="panel.link" 
                            class="inline-flex items-center text-sm font-bold text-brand-600 uppercase tracking-wider hover:text-brand-800 transition-colors"
                        >
                            {{ panel.title === 'Accessible Education' ? 'Tuition & Scholarships' : 
                               (panel.title === 'Family Partnership' ? 'Silver Parent Circle' : 'Learn More') }}
                            <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats & Grade Levels Section -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center mb-24">
                    <div v-for="stat in stats" :key="stat.label" class="bg-brand-50 rounded-lg p-6">
                        <div class="text-5xl md:text-6xl font-serif font-bold text-brand-500 mb-4">{{ stat.value }}</div>
                        <div class="text-brand-500 font-medium text-lg leading-tight max-w-[200px] mx-auto">{{ stat.label }}</div>
                    </div>
                </div>

                <!-- Section Title -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 tracking-tight mb-6 whitespace-nowrap">
                        Growing Confident, Compassionate, and Curious Students
                    </h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        At The Silver Academy, our small classes and integrated general and Judaic curriculum 
                        give Pre-K through 8th grade students the personalized attention they need to thrive. 
                        We welcome families from all backgrounds and nurture each child’s character, critical 
                        thinking, and sense of purpose as they grow academically, emotionally, and spiritually.
                    </p>
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
                                    <p class="text-white/90 mb-6 leading-relaxed">
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

        <!-- Family Community Section -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <!-- Star of David Icon -->
                    <div class="flex justify-center mb-8">
                        <img src="/img/icons/TSA Logo-inverted.svg" alt="Star of David" class="w-[116px] h-[116px] text-brand-500" />
                    </div>
                    
                    <!-- Headline -->
                    <h2 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 tracking-tight mb-6">
                        Be Part of a School That Feels Like Family
                    </h2>
                    
                    <!-- Descriptive Text -->
                    <p class="text-lg sm:text-xl text-slate-600 leading-relaxed mb-10 max-w-3xl mx-auto">
                        The Silver Academy welcomes families from all backgrounds into a warm, inclusive community. 
                        Together, we nurture children who are proud of who they are and confident in where they're going.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            href="/contact"
                            class="px-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-800 bg-white border-2 border-accent-500 rounded hover:bg-accent-500 hover:text-white transition-colors duration-200"
                        >
                            Inquire
                        </Link>
                        <Link
                            href="/contact"
                            class="px-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-800 bg-white border-2 border-accent-500 rounded hover:bg-accent-500 hover:text-white transition-colors duration-200"
                        >
                            Visit
                        </Link>
                        <Link
                            href="#"
                            class="px-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-800 bg-white border-2 border-accent-500 rounded hover:bg-accent-500 hover:text-white transition-colors duration-200"
                        >
                            Apply
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="relative py-0">
            <div class="relative h-[600px] md:h-[700px]">
                <!-- Background Image -->
                <img 
                    src="https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=1920&q=80" 
                    alt="Happy student at Silver Academy" 
                    class="absolute inset-0 w-full h-full object-cover"
                />
                
                <!-- Testimonial Quote Overlay -->
                <div class="absolute inset-0 flex items-center">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8 w-full">
                        <div class="max-w-2xl bg-white/95 backdrop-blur-sm rounded-2xl p-8 md:p-12 shadow-2xl relative">
                            <!-- Large Quotation Mark -->
                            <div class="absolute -top-4 -left-4">
                                <svg class="w-16 h-16 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.996 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.984zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                </svg>
                            </div>
                            
                            <!-- Quote Text -->
                            <p class="text-lg md:text-xl text-slate-700 leading-relaxed mb-6 pt-4">
                                From the sense of community, small class sizes, warm and amazing staff and faculty—we were 
                                blessed to be a part of the Silver team! As a non-Jewish family, we loved that we could give 
                                our daughter the gift of Inclusion, learning about other cultures and religions and an 
                                opportunity to see new perspectives.
                            </p>
                            
                            <!-- Attribution -->
                            <p class="text-right text-slate-600 font-medium">
                                —PARENT
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </MarketingLayout>
</template>

<style scoped>
.writing-vertical-lr {
    writing-mode: vertical-lr;
}
</style>
