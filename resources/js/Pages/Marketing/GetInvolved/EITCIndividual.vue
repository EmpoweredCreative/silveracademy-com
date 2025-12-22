<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';
import { 
    AcademicCapIcon, 
    CurrencyDollarIcon, 
    UserGroupIcon,
    ArrowRightIcon,
    ArrowDownTrayIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline';
</script>

<template>
    <Head title="Individual EITC - The Silver Academy" />

    <MarketingLayout>
        <!-- Hero Section -->
        <section class="relative h-[350px] overflow-hidden">
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1577896335477-28d5d43603c4?auto=format&fit=crop&w=1920&q=80" 
                    alt="Individual EITC Giving" 
                    class="w-full h-full object-cover object-center"
                />
                <div class="absolute inset-0 bg-brand-900/80 mix-blend-multiply"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center text-center">
                <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6">
                        Individual EITC Giving
                    </h1>
                    <p class="text-xl text-brand-100 font-medium max-w-2xl mx-auto">
                        Support Students Through Pennsylvania’s Educational Improvement Tax Credit Program
                    </p>
                </div>
            </div>
        </section>

        <!-- Intro -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-lg text-slate-700 leading-relaxed mb-8">
                    The Silver Academy is proud to participate in Pennsylvania’s Educational Improvement Tax Credit (EITC) program, which allows individuals to redirect state taxes to support need-based scholarships for students. Your participation makes a Silver Academy education accessible to families who might otherwise be unable to afford it, ensuring every child can thrive academically, socially, and spiritually.
                </p>
            </div>
        </section>

        <!-- How It Works -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-serif font-bold text-slate-900">How It Works</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ArrowRightIcon class="w-6 h-6 text-brand-600" />
                        </div>
                        <h3 class="font-bold text-lg mb-2">Redirect Taxes</h3>
                        <p class="text-slate-600">Redirect your PA state taxes to benefit The Silver Academy.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <AcademicCapIcon class="w-6 h-6 text-brand-600" />
                        </div>
                        <h3 class="font-bold text-lg mb-2">Fund Scholarships</h3>
                        <p class="text-slate-600">Contributions fund scholarships for low- and middle-income families.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <CurrencyDollarIcon class="w-6 h-6 text-brand-600" />
                        </div>
                        <h3 class="font-bold text-lg mb-2">Full Credit</h3>
                        <p class="text-slate-600">Receive a dollar-for-dollar credit against your PA state taxes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Impact Stats -->
        <section class="py-16 bg-brand-600 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 text-center">
                    <div>
                        <div class="text-5xl font-bold mb-2">61%</div>
                        <p class="text-xl text-brand-100">of students receive scholarships</p>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">$17,105</div>
                        <p class="text-xl text-brand-100">Average scholarship award</p>
                    </div>
                </div>
                <p class="text-center mt-12 text-lg font-medium text-brand-100">
                    Your EITC contribution helps ensure no child is turned away due to financial need.
                </p>
            </div>
        </section>

        <!-- Steps to Participate -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8 text-center">How to Participate</h2>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center">1</div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Check Your Eligibility</h3>
                            <p class="text-slate-600">EITC participation is available to Pennsylvania taxpayers.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center">2</div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Redirect Your State Taxes</h3>
                            <p class="text-slate-600">Designate your EITC contribution to The Silver Academy (through the Jewish Federation of Greater Harrisburg – SO, Scholarship Organization).</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center">3</div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Submit Your Contribution</h3>
                            <p class="text-slate-600">Your donation funds scholarships for students in need, making a lasting impact on the next generation.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 transition-colors">
                        <ArrowDownTrayIcon class="w-5 h-5 mr-2" />
                        Download Fact Sheet
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-brand-200 text-base font-medium rounded-md text-brand-700 bg-brand-50 hover:bg-brand-100 transition-colors">
                        <DocumentTextIcon class="w-5 h-5 mr-2" />
                        Step-By-Step Instructions
                    </a>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Questions?</h2>
                <p class="text-slate-600 mb-8">Contact us for guidance and to learn more:</p>
                
                <div class="bg-white p-8 rounded-xl shadow-sm inline-block text-left">
                    <p class="font-bold text-lg text-slate-900">Gila Ogle</p>
                    <p class="text-slate-600 mb-4">Head of School</p>
                    <div class="space-y-2">
                        <p class="flex items-center text-brand-600">
                            <span class="font-medium mr-2">Phone:</span>
                            <a href="tel:717-238-8775" class="hover:text-brand-700">717-238-8775</a>
                        </p>
                        <p class="flex items-center text-brand-600">
                            <span class="font-medium mr-2">Email:</span>
                            <a href="mailto:gogle@silveracademypa.org" class="hover:text-brand-700">gogle@silveracademypa.org</a>
                        </p>
                    </div>
                </div>
                
                <p class="mt-8 text-sm text-slate-500">
                    Or visit the <a href="#" class="text-brand-600 hover:underline">PA DCED EITC website</a> for additional instructions and eligibility details.
                </p>
            </div>
        </section>
    </MarketingLayout>
</template>
