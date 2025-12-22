<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';
import { 
    HeartIcon, 
    BuildingOfficeIcon, 
    SparklesIcon, 
    ShoppingBagIcon 
} from '@heroicons/vue/24/outline';

const opportunities = [
    {
        title: 'Make a Donation',
        description: 'Support our programs, faculty, and students through a general donation. Every gift helps ensure that every child has access to an exceptional Jewish day school experience.',
        icon: HeartIcon,
        link: '/get-involved/donate',
        isExternal: false,
        action: 'Make a Donation',
        color: 'bg-brand-100 text-brand-600',
    },
    {
        title: 'EITC (Individual & Corporate)',
        description: 'Redirect your PA state taxes to support scholarships for Silver Academy students. Both individuals and businesses can participate, making a lasting impact while benefiting from tax credits.',
        icon: BuildingOfficeIcon,
        isEitc: true,
        color: 'bg-brand-100 text-brand-600',
    },
    {
        title: 'Life & Legacy',
        description: 'Planned giving helps secure Silver Academy’s future for generations to come. Leave a lasting legacy and join those who are investing in Jewish education and community leadership.',
        icon: SparklesIcon,
        link: '/get-involved/life-and-legacy',
        isExternal: false,
        action: 'Join Life & Legacy',
        color: 'bg-brand-100 text-brand-600',
    },
    {
        title: 'Fundraisers',
        description: 'Participate in our fundraising programs to support the school and engage with the community: Giant Dollar for Dollar, Boxtops, Shop with Scrip, and more.',
        icon: ShoppingBagIcon,
        link: '/get-involved/fundraisers',
        isExternal: false,
        action: 'View Fundraisers',
        color: 'bg-brand-100 text-brand-600',
    }
];
</script>

<template>
    <Head title="Get Involved - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1920&q=80" 
                    alt="Community support" 
                    class="w-full h-full object-cover object-center"
                />
                <div class="absolute inset-0 bg-brand-900/60 mix-blend-multiply"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center text-center">
                <div class="max-w-3xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6">
                        Get Involved
                    </h1>
                    <p class="text-xl md:text-2xl text-brand-50 font-medium max-w-2xl mx-auto">
                        Your support helps us provide a vibrant, values-driven education for every child.
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-serif font-bold text-slate-900 sm:text-4xl">Make a Difference</h2>
                    <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">
                        There are many ways to get involved with The Silver Academy. Whether through direct giving, tax credit programs, planned giving, or community fundraisers, your contribution matters.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                    <div 
                        v-for="item in opportunities" 
                        :key="item.title"
                        class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col"
                    >
                        <div class="p-8 flex-1">
                            <div :class="['w-14 h-14 rounded-full flex items-center justify-center mb-6', item.color]">
                                <component :is="item.icon" class="w-8 h-8" />
                            </div>
                            <h3 class="text-2xl font-serif font-bold text-slate-900 mb-4">
                                {{ item.title }}
                            </h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                {{ item.description }}
                            </p>
                        </div>
                        <div class="px-8 pb-8 pt-0 mt-auto">
                            <!-- Special handling for EITC with two buttons -->
                            <div v-if="item.isEitc" class="flex flex-col sm:flex-row gap-3">
                                <Link 
                                    href="/get-involved/eitc-individual"
                                    class="inline-flex items-center text-brand-600 font-semibold hover:text-brand-700 transition-colors group"
                                >
                                    Individual
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </Link>
                                <span class="hidden sm:inline text-slate-300">|</span>
                                <Link 
                                    href="/get-involved/eitc-corporate"
                                    class="inline-flex items-center text-brand-600 font-semibold hover:text-brand-700 transition-colors group"
                                >
                                    Corporate
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </Link>
                            </div>

                            <!-- Standard single button -->
                            <template v-else>
                                <a 
                                    v-if="item.isExternal"
                                    :href="item.link"
                                    class="inline-flex items-center text-brand-600 font-semibold hover:text-brand-700 transition-colors group"
                                >
                                    {{ item.action }}
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                                <Link 
                                    v-else
                                    :href="item.link"
                                    class="inline-flex items-center text-brand-600 font-semibold hover:text-brand-700 transition-colors group"
                                >
                                    {{ item.action }}
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-4">
                    Have questions about giving?
                </h2>
                <p class="text-slate-600 mb-8 max-w-2xl mx-auto">
                    We'd love to discuss how you can make a meaningful impact. Contact our development office to learn more about giving opportunities.
                </p>
                <Link
                    href="/contact"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 transition-colors shadow-sm"
                >
                    Contact Us
                </Link>
            </div>
        </section>
    </MarketingLayout>
</template>

