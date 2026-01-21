<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ClockIcon, EnvelopeIcon, PhoneIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <Head title="Registration Pending Approval" />

    <GuestLayout>
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                <ClockIcon class="w-8 h-8 text-amber-600" />
            </div>
            
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Registration Received</h1>
            <p class="text-slate-600 mb-6">Thank you for registering!</p>

            <div class="bg-slate-50 rounded-xl p-6 text-left mb-6">
                <h2 class="font-semibold text-slate-900 mb-3">What happens next?</h2>
                <ol class="space-y-3 text-sm text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">1</span>
                        <span>Your registration is being reviewed by school administration.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">2</span>
                        <span>Once approved, you will receive an email with your login credentials.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">3</span>
                        <span>Use those credentials to log in and access the Family Portal.</span>
                    </li>
                </ol>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 text-blue-800 mb-2">
                    <EnvelopeIcon class="w-5 h-5" />
                    <span class="font-medium">Check your email</span>
                </div>
                <p class="text-sm text-blue-700">
                    We've sent a confirmation email to the address you provided. You'll receive another email once your account is approved.
                </p>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <p class="text-sm text-slate-600 mb-4">Have questions? Contact the school office:</p>
                <div class="flex flex-col items-center gap-2 text-sm">
                    <a href="mailto:office@silveracademypa.org" class="flex items-center gap-2 text-brand-600 hover:text-brand-500">
                        <EnvelopeIcon class="w-4 h-4" />
                        office@silveracademypa.org
                    </a>
                    <a href="tel:+1234567890" class="flex items-center gap-2 text-brand-600 hover:text-brand-500">
                        <PhoneIcon class="w-4 h-4" />
                        (412) 421-7040
                    </a>
                </div>
            </div>

            <div class="mt-8">
                <Link 
                    href="/" 
                    class="text-sm text-slate-500 hover:text-slate-700"
                >
                    &larr; Return to homepage
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
