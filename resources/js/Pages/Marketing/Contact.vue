<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    // Form submission will be implemented later
    alert('Thank you for your message! We will get back to you soon.');
    form.reset();
};
</script>

<template>
    <Head title="Contact Us" />

    <MarketingLayout>
        <section class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16">
                    <!-- Contact Info -->
                    <div>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight tracking-tight">
                            Get in Touch
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-slate-600">
                            Have questions about Silver Academy? We'd love to hear from you. 
                            Send us a message and we'll respond as soon as possible.
                        </p>

                        <div class="mt-12 space-y-8">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center text-2xl shrink-0">
                                    📧
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Email</h3>
                                    <p class="text-slate-600">hello@silveracademy.com</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center text-2xl shrink-0">
                                    📞
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Phone</h3>
                                    <p class="text-slate-600">(555) 123-4567</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center text-2xl shrink-0">
                                    📍
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Address</h3>
                                    <p class="text-slate-600">123 Education Lane<br>Learning City, LC 12345</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Name
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                                    placeholder="Your name"
                                />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                                    placeholder="you@example.com"
                                />
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">
                                    Subject
                                </label>
                                <input
                                    id="subject"
                                    v-model="form.subject"
                                    type="text"
                                    required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                                    placeholder="How can we help?"
                                />
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-slate-700 mb-2">
                                    Message
                                </label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    rows="4"
                                    required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border resize-none"
                                    placeholder="Your message..."
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full rounded-lg bg-brand-600 px-6 py-4 text-base font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
                            >
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>

