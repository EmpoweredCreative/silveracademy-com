<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { UserGroupIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    name: '',
    email: '',
    is_parent_confirmed: false,
    terms_accepted: false,
});

const submit = () => {
    form.post('/register');
};
</script>

<template>
    <Head title="Parent Registration" />

    <GuestLayout>
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-brand-100 rounded-full flex items-center justify-center mb-4">
                <UserGroupIcon class="w-8 h-8 text-brand-600" />
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Parent Registration</h1>
            <p class="text-slate-600 mt-2">Register for the Silver Academy Family Portal</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> This registration is for parents only. Your account will be reviewed by school administration before you can access the portal. Once approved, you will receive an email with your login credentials.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    Full Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.name }"
                    placeholder="Your full name"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email Address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="email"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.email }"
                    placeholder="you@example.com"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <!-- Parent Confirmation Checkbox -->
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="is_parent_confirmed"
                            v-model="form.is_parent_confirmed"
                            type="checkbox"
                            class="w-4 h-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500"
                            :class="{ 'border-red-500': form.errors.is_parent_confirmed }"
                        />
                    </div>
                    <div class="ml-3">
                        <label for="is_parent_confirmed" class="text-sm text-slate-700">
                            I hereby confirm that I am a parent or legal guardian of a student at Silver Academy.
                        </label>
                        <p v-if="form.errors.is_parent_confirmed" class="mt-1 text-sm text-red-600">
                            {{ form.errors.is_parent_confirmed }}
                        </p>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="terms_accepted"
                            v-model="form.terms_accepted"
                            type="checkbox"
                            class="w-4 h-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500"
                            :class="{ 'border-red-500': form.errors.terms_accepted }"
                        />
                    </div>
                    <div class="ml-3">
                        <label for="terms_accepted" class="text-sm text-slate-700">
                            I agree to adhere to the terms and conditions of using the Silver Academy website.
                        </label>
                        <p v-if="form.errors.terms_accepted" class="mt-1 text-sm text-red-600">
                            {{ form.errors.terms_accepted }}
                        </p>
                    </div>
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing || !form.is_parent_confirmed || !form.terms_accepted"
                class="w-full rounded-lg bg-brand-600 px-6 py-4 text-base font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="!form.processing">Submit Registration</span>
                <span v-else>Submitting...</span>
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-600">
                Already have an account?
                <Link href="/login" class="font-medium text-brand-600 hover:text-brand-500">
                    Sign in
                </Link>
            </p>
        </div>

        <div class="mt-4 text-center">
            <p class="text-xs text-slate-500">
                Staff members are added to the system by school administration. Please contact the school office if you are a staff member needing access.
            </p>
        </div>
    </GuestLayout>
</template>
