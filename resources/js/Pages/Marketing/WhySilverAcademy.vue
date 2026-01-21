<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const approaches = [
    {
        title: 'Dual Excellence',
        description: 'Rigorous general and Judaic studies are fully integrated, giving students a strong academic foundation while nurturing Jewish identity and values.'
    },
    {
        title: 'Small Class Sizes & Personalized Learning',
        description: 'With no more than 12 students per class, every child is seen, heard, and supported. Individualized attention ensures both challenge and support where needed.'
    },
    {
        title: 'Jewish Identity & Pride',
        description: 'Our students explore Torah, tradition, and Jewish values in meaningful ways, building confidence and pride in who they are.'
    },
    {
        title: 'Restorative, Supportive Culture',
        description: 'Children learn responsibility, accountability, and emotional intelligence in a safe, inclusive environment.'
    },
    {
        title: 'Holistic, Hands-On Learning',
        description: 'Art, music, science, and project-based experiences foster curiosity, creativity, and joy across subjects.'
    },
    {
        title: 'Family Partnership',
        description: 'Transparent communication, ongoing dialogue, and community programs ensure families are true partners in education.'
    },
    {
        title: 'Accessible Education',
        description: 'Generous financial aid and EITC programs ensure all families can access a high-quality day school experience.'
    }
];

const differences = [
    'Integrated Judaic + Secular Curriculum',
    'Exceptionally small class sizes',
    'Strong, proud Jewish identity',
    'Restorative, supportive culture',
    'Holistic, cross-disciplinary learning',
    'Genuine family partnership'
];

const tuitionInfo = [
    { grade: 'Ganeinu', price: '$2,000' },
    { grade: 'Kindergarten', price: '$10,000' },
    { grade: 'Grade 1', price: '$32,500' },
    { grade: 'Grades 2–8', price: '$35,000' }
];

const schoolHighlights = {
    academics: [
        'Integrated general + Judaic curriculum',
        'Hands-on programs in art, music, STEM',
        'Advanced technology program: robotics, coding, engineering design',
        'ADAM Intelligence framework: skills for the AI era'
    ],
    community: [
        'Inclusive, welcoming Jewish community open to all',
        'Mentor Family program and Parent Circle',
        'Intergenerational "Better Together" program with local seniors',
        'Collaborations with local synagogues and PJ Library'
    ],
    faculty: [
        'Passionate educators blending experience with new perspectives',
        'Ongoing professional development, restorative practices, and advanced certifications'
    ],
    facilities: [
        'New playground and expanded athletic opportunities',
        'Exploring creative campus expansions to support growing enrollment'
    ]
};
</script>

<template>
    <Head title="Why Silver Academy? - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="/img/graphics/about/web-silveracademyWhy Silver.jpg" 
                    alt="Students at The Silver Academy" 
                    class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-black/20"></div>
            </div>
        </section>

        <!-- Intro Section -->
        <section class="py-20 bg-white text-center">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight mb-6">
                    A School Where Every Child is Seen, Supported, and Inspired
                </h1>
                <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                    Small classes, dual excellence, and a values-driven education prepare Jewish students to thrive academically, socially, and spiritually.
                </p>
            </div>
        </section>

        <!-- Our Approach Section -->
        <section id="our-approach" class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl text-slate-800 font-bold mb-4">Our Approach</h2>
                    <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                        Choosing a school is one of the most important decisions a family makes. At The Silver Academy, we understand that families want more than academics, they want a place where children are known, challenged, and empowered to grow into confident, compassionate, and capable young people.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="(approach, index) in approaches" 
                        :key="index"
                        class="relative bg-white p-8 rounded-xl shadow-sm border-t-4 border-brand-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden"
                    >
                        <!-- Logo Watermark -->
                        <img 
                            src="/img/icons/safavicon.png" 
                            alt=""
                            class="absolute -right-6 -bottom-6 w-40 h-40 opacity-5 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none select-none z-0 grayscale"
                        />

                        <div class="relative z-10">
                            <h3 class="font-serif font-bold text-brand-700 text-2xl mb-3 group-hover:text-brand-600 transition-colors duration-300">
                                {{ approach.title }}
                            </h3>
                            <p class="text-slate-600 leading-relaxed">
                                {{ approach.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- The Silver Academy Difference -->
        <section class="py-24 bg-brand-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-serif text-4xl text-slate-800 tracking-wide uppercase mb-4">
                        The Silver Academy Difference
                    </h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <div 
                        v-for="(difference, index) in differences" 
                        :key="index"
                        class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-accent-500"
                    >
                        <p class="text-slate-700 font-semibold">{{ difference }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section - Meet Our Students -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <Link 
                    href="/services" 
                    class="inline-block bg-accent-500 text-white px-8 py-4 rounded font-semibold uppercase tracking-wider hover:bg-accent-600 transition-colors duration-200"
                >
                    Meet Our Students →
                </Link>
            </div>
        </section>

        <!-- Tuition & Financial Aid Section -->
        <section class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="font-serif text-4xl text-slate-800 tracking-wide uppercase mb-4">
                            Accessible Education
                        </h2>
                        <p class="text-lg text-slate-600">
                            Generous financial aid and EITC programs ensure all families can access a high-quality day school experience.
                        </p>
                    </div>

                    <!-- Financial Aid Statistics - Prominent -->
                    <div class="bg-brand-500 text-white p-10 md:p-12 rounded-2xl shadow-xl mb-8">
                        <div class="text-center mb-8">
                            <h3 class="font-serif text-3xl md:text-4xl font-bold text-white mb-2">
                                Making Education Accessible
                            </h3>
                            <p class="text-white/90 text-lg">
                                Most families receive significant financial assistance
                            </p>
                        </div>
                        <div class="grid md:grid-cols-3 gap-8 text-center mb-8">
                            <div>
                                <div class="text-5xl md:text-6xl font-bold mb-3">61%</div>
                                <div class="text-white/90 text-base font-medium">of students receive a scholarship</div>
                            </div>
                            <div>
                                <div class="text-5xl md:text-6xl font-bold mb-3">60.2%</div>
                                <div class="text-white/90 text-base font-medium">Average scholarship award</div>
                            </div>
                            <div>
                                <div class="text-5xl md:text-6xl font-bold mb-3">$17,105</div>
                                <div class="text-white/90 text-base font-medium">Average scholarship amount</div>
                            </div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center">
                            <p class="text-white text-sm uppercase tracking-wider mb-2">Average Tuition Paid</p>
                            <p class="text-white text-4xl font-bold">$8,178.90</p>
                        </div>
                    </div>

                    <!-- Full-Cost Tuition - De-emphasized -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
                        <h3 class="font-serif text-lg text-slate-600 mb-4 text-center">Full-Cost Tuition (Before Financial Aid)</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div 
                                v-for="(item, index) in tuitionInfo" 
                                :key="index"
                                class="border-b border-slate-100 pb-3"
                            >
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 text-sm">{{ item.grade }}</span>
                                    <span class="text-slate-400 font-medium text-sm">{{ item.price }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- School Highlights -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-4xl text-slate-800 tracking-wide uppercase mb-4">
                        School Highlights
                    </h2>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Academics & Curriculum -->
                    <div class="bg-slate-50 p-8 rounded-xl">
                        <h3 class="font-serif text-2xl text-brand-600 mb-6">Academics & Curriculum</h3>
                        <ul class="space-y-3">
                            <li 
                                v-for="(item, index) in schoolHighlights.academics" 
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-accent-500 mr-3 mt-1">•</span>
                                <span class="text-slate-700">{{ item }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Community & Families -->
                    <div class="bg-slate-50 p-8 rounded-xl">
                        <h3 class="font-serif text-2xl text-brand-600 mb-6">Community & Families</h3>
                        <ul class="space-y-3">
                            <li 
                                v-for="(item, index) in schoolHighlights.community" 
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-accent-500 mr-3 mt-1">•</span>
                                <span class="text-slate-700">{{ item }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Faculty & Staff -->
                    <div class="bg-slate-50 p-8 rounded-xl">
                        <h3 class="font-serif text-2xl text-brand-600 mb-6">Faculty & Staff</h3>
                        <ul class="space-y-3">
                            <li 
                                v-for="(item, index) in schoolHighlights.faculty" 
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-accent-500 mr-3 mt-1">•</span>
                                <span class="text-slate-700">{{ item }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Facilities & Campus Updates -->
                    <div class="bg-slate-50 p-8 rounded-xl">
                        <h3 class="font-serif text-2xl text-brand-600 mb-6">Facilities & Campus Updates</h3>
                        <ul class="space-y-3">
                            <li 
                                v-for="(item, index) in schoolHighlights.facilities" 
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-accent-500 mr-3 mt-1">•</span>
                                <span class="text-slate-700">{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section - Support Silver -->
        <section class="py-16 bg-brand-600">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <Link 
                    href="/get-involved" 
                    class="inline-block bg-white text-brand-600 px-8 py-4 rounded font-semibold uppercase tracking-wider hover:bg-slate-50 transition-colors duration-200"
                >
                    Support Silver →
                </Link>
            </div>
        </section>
    </MarketingLayout>
</template>
