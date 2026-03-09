<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { computed } from 'vue';

const page = usePage();
const statusMessage = computed(() => page.props.status ?? page.props.flash?.status ?? '');

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password', {
        preserveState: false,
        onSuccess: () => {
            // Ensure we show the success message after redirect
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Forgot password" />

    <GuestLayout>
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Forgot password?</h1>
            <p class="text-slate-600 mt-2">Enter your email and we'll send you a link to reset your password.</p>
        </div>

        <div v-if="statusMessage" class="mb-4 p-4 font-medium text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg text-center">
            {{ statusMessage }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.email }"
                    placeholder="you@example.com"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-lg bg-brand-600 px-6 py-4 text-base font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
            >
                <span v-if="!form.processing">Email password reset link</span>
                <span v-else>Sending...</span>
            </button>
        </form>

        <div class="mt-6 text-center">
            <Link href="/login" class="text-sm text-brand-600 hover:text-brand-500 font-medium">
                &larr; Back to sign in
            </Link>
        </div>
    </GuestLayout>
</template>
