<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 via-white to-brand-50 px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <img 
                        src="/img/logo/silveracademylogo.png" 
                        alt="The Silver Academy - Central PA's Jewish Day School" 
                        class="h-12 sm:h-14 w-auto mx-auto"
                    />
                </Link>
            </div>

            <div class="bg-white shadow-xl rounded-2xl p-8 border border-slate-200/60">
                <slot />
            </div>

            <div class="mt-6 text-center">
                <Link href="/" class="text-sm text-slate-600 hover:text-brand-600 transition-colors">
                    &larr; Back to home
                </Link>
            </div>
        </div>
    </div>
</template>


