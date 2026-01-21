<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const whyHarrisburg = [
    {
        number: '1.',
        title: 'Rich Jewish History & Vibrancy',
        description: "Harrisburg's Jewish community has deep roots and a strong commitment to education, philanthropy, and shared values."
    },
    {
        number: '2.',
        title: 'Central Location',
        description: 'Families from Hershey, York, Lancaster, and surrounding areas can easily access The Silver Academy, making it a regional hub for Jewish learning.'
    },
    {
        number: '3.',
        title: 'Partnerships Across the Community',
        description: 'The Silver Academy collaborates with local synagogues, the Jewish Federation, PJ Library, JCC, and other community organizations, creating meaningful opportunities for students and families alike.'
    },
    {
        number: '4.',
        title: 'Family-Centered Programs',
        description: 'From Mentor Families and Parent Circle gatherings to intergenerational programs like Better Together, we foster connections that extend beyond the classroom.'
    }
];

const communityPartners = [
    { name: 'Jewish Federation of Greater Harrisburg / Jewish Community Center', url: '#' },
    { name: 'Jewish Family Services', url: '#' },
    { name: 'The Campus of the Jewish Home of Greater Harrisburg', url: '#' },
    { name: 'Beth El Temple', url: '#' },
    { name: 'Chisuk Emuna Congregation', url: '#' },
    { name: 'Kesher Israel', url: '#' },
    { name: 'Temple Beth Shalom', url: '#' },
    { name: 'Temple Ohev Sholom', url: '#' }
];

const networkItems = [
    {
        number: '1.',
        title: 'Parent Engagement',
        description: 'Families participate in events, workshops, and volunteer opportunities that strengthen the school-community bond.'
    },
    {
        number: '2.',
        title: 'Intergenerational Connections',
        description: 'Students partner with seniors in the community, building empathy, tradition, and shared memories.'
    },
    {
        number: '3.',
        title: 'Cultural & Social Experiences',
        description: 'Jewish holidays, traditions, and values are woven into every program, ensuring students grow with pride in their identity and a sense of responsibility to their community.'
    }
];

const commitmentItems = [
    {
        number: '1.',
        title: 'Accessible Education',
        description: 'Financial aid and EITC programs make high-quality Jewish education attainable for families of diverse incomes.'
    },
    {
        number: '2.',
        title: 'Safe & Inclusive Environment',
        description: 'We celebrate diversity across denominations and family structures, ensuring every child and parent feels seen and valued.'
    },
    {
        number: '3.',
        title: 'Connected Community',
        description: 'Students form friendships that last a lifetime, families build supportive networks, and alumni carry these connections forward into the world.'
    }
];
</script>

<template>
    <Head title="Our Community - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="/img/graphics/about/web-silveracademyCommunity 2.jpg" 
                    alt="Silver Academy Community" 
                    class="w-full h-full object-cover object-center"
                />
                <div class="absolute inset-0 bg-black/20"></div>
            </div>
        </section>

        <!-- Intro Section -->
        <section class="py-20 bg-white text-center">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight mb-6">
                        Where Jewish Life Thrives in Central Pennsylvania
                    </h1>
                    <p class="text-lg sm:text-xl text-slate-600 leading-relaxed mb-6">
                        The Silver Academy is proud to be at the heart of a vibrant, welcoming, and diverse Jewish community in Harrisburg and beyond.
                    </p>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-3xl mx-auto">
                        At The Silver Academy, community is at the center of everything we do. Our families, faculty, and partners create a warm, inclusive environment where Jewish values are lived, celebrated, and shared. Students don't just learn. They belong. Families don't just support. They connect. Together, we build a foundation of pride, purpose, and lasting relationships.
                    </p>
                </div>
            </div>
        </section>

        <!-- Why Harrisburg Section -->
        <section class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold mb-4 text-center">
                        Why Harrisburg?
                    </h2>
                    <p class="text-lg text-slate-600 mb-12 text-center leading-relaxed">
                        Harrisburg is more than a location, it's a home for Jewish families seeking connection, culture, and opportunity:
                    </p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div 
                            v-for="item in whyHarrisburg" 
                            :key="item.number"
                            class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300"
                        >
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-brand-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ item.number.replace('.', '') }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-slate-800 mb-3">{{ item.title }}</h3>
                                    <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Neighborhood & Community Partners Section -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold mb-4 text-center">
                        Our Neighborhood & Community Partners
                    </h2>
                    <p class="text-lg text-slate-600 mb-8 text-center leading-relaxed">
                        The Silver Academy finds its home in the Jewish Community Center of Harrisburg on Front Street in the Uptown neighborhood. Our community is home to five welcoming synagogues and many vibrant Jewish agencies. Below are links to explore these organizations:
                    </p>

                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <ul class="space-y-4">
                            <li 
                                v-for="(partner, index) in communityPartners" 
                                :key="index"
                                class="flex items-center group"
                            >
                                <div class="w-2 h-2 bg-brand-500 rounded-full mr-4 flex-shrink-0"></div>
                                <a 
                                    :href="partner.url" 
                                    class="text-brand-600 hover:text-brand-800 font-medium transition-colors text-lg group-hover:underline"
                                >
                                    {{ partner.name }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8 bg-brand-50 p-6 rounded-lg border-l-4 border-accent-500">
                        <p class="text-slate-700 leading-relaxed">
                            <strong>New to the Harrisburg Jewish Community?</strong> Contact Amanda Kohn or Lori Rubin of the Welcoming Committee at 
                            <a href="tel:717-236-9555" class="text-brand-600 hover:text-brand-800 font-semibold">717-236-9555</a>. 
                            They are happy to help you get acquainted with our warm and active community.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Network of Learning & Support Section -->
        <section class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold mb-4 text-center">
                        A Network of Learning & Support
                    </h2>
                    <p class="text-lg text-slate-600 mb-12 text-center leading-relaxed">
                        At The Silver Academy, education extends beyond academics:
                    </p>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div 
                            v-for="item in networkItems" 
                            :key="item.number"
                            class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center"
                        >
                            <div class="w-16 h-16 bg-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">{{ item.number.replace('.', '') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ item.title }}</h3>
                            <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Commitment to Families Section -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="font-serif text-3xl sm:text-4xl text-slate-800 font-bold mb-4 text-center">
                        Our Commitment to Families
                    </h2>
                    <p class="text-lg text-slate-600 mb-12 text-center leading-relaxed">
                        The Silver Academy believes every child deserves access to an excellent education and a vibrant community:
                    </p>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div 
                            v-for="item in commitmentItems" 
                            :key="item.number"
                            class="bg-slate-50 p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 border-t-4 border-accent-500"
                        >
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ item.title }}</h3>
                            <p class="text-slate-600 leading-relaxed">{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-brand-900 text-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-6">
                        Join Our Community
                    </h2>
                    <p class="text-lg text-brand-100 mb-8 leading-relaxed">
                        Discover how The Silver Academy can be part of your family's journey.
                    </p>
                    <Link 
                        href="/admissions"
                        class="inline-block bg-accent-500 text-white px-10 py-4 rounded font-bold uppercase tracking-wider hover:bg-accent-600 transition-colors shadow-lg hover:shadow-xl"
                    >
                        Join Our Community →
                    </Link>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
