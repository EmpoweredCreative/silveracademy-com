<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { UserIcon, AcademicCapIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    staffEmailDomain: {
        type: String,
        default: 'silveracademypa.org',
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    account_type: 'parent', // Default to parent
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <GuestLayout>
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Create your account</h1>
            <p class="text-slate-600 mt-2">Join the Silver Academy portal</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Account Type Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-3">
                    I am registering as:
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label 
                        :class="[
                            'relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                            form.account_type === 'parent' 
                                ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-500/20' 
                                : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                        ]"
                    >
                        <input 
                            type="radio" 
                            v-model="form.account_type" 
                            value="parent" 
                            class="sr-only" 
                        />
                        <UserIcon 
                            :class="[
                                'w-8 h-8 mb-2',
                                form.account_type === 'parent' ? 'text-brand-600' : 'text-slate-400'
                            ]" 
                        />
                        <span 
                            :class="[
                                'font-semibold text-sm',
                                form.account_type === 'parent' ? 'text-brand-700' : 'text-slate-700'
                            ]"
                        >
                            Parent
                        </span>
                        <span class="text-xs text-slate-500 mt-1 text-center">
                            Access your child's info
                        </span>
                    </label>
                    <label 
                        :class="[
                            'relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                            form.account_type === 'staff' 
                                ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-500/20' 
                                : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                        ]"
                    >
                        <input 
                            type="radio" 
                            v-model="form.account_type" 
                            value="staff" 
                            class="sr-only" 
                        />
                        <AcademicCapIcon 
                            :class="[
                                'w-8 h-8 mb-2',
                                form.account_type === 'staff' ? 'text-brand-600' : 'text-slate-400'
                            ]" 
                        />
                        <span 
                            :class="[
                                'font-semibold text-sm',
                                form.account_type === 'staff' ? 'text-brand-700' : 'text-slate-700'
                            ]"
                        >
                            Staff Member
                        </span>
                        <span class="text-xs text-slate-500 mt-1 text-center">
                            School email required
                        </span>
                    </label>
                </div>
                <p v-if="form.account_type === 'staff'" class="mt-2 text-xs text-slate-500">
                    Staff registration requires a @{{ staffEmailDomain }} email address.
                </p>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    Full name
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
                    placeholder="John Doe"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.email }"
                    :placeholder="form.account_type === 'staff' ? `you@${staffEmailDomain}` : 'you@example.com'"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                    Password
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.password }"
                    placeholder="••••••••"
                />
                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                    Confirm password
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
                    :class="{ 'border-red-500': form.errors.password_confirmation }"
                    placeholder="••••••••"
                />
                <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-lg bg-brand-600 px-6 py-4 text-base font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
            >
                <span v-if="!form.processing">Create account</span>
                <span v-else>Creating account...</span>
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
    </GuestLayout>
</template>
