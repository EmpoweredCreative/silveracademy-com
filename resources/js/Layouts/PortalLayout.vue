<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user;

const sidebarOpen = ref(false);

const navigation = [
    { name: 'Dashboard', href: '/portal/dashboard', icon: 'home' },
    { name: 'Messages', href: '/portal/messages', icon: 'envelope' },
    { name: 'Calendar', href: '/portal/calendar', icon: 'calendar' },
    { name: 'Documents', href: '/portal/documents', icon: 'folder' },
    { name: 'Settings', href: '/portal/settings', icon: 'cog' },
];

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Mobile Sidebar Backdrop -->
        <div
            v-show="sidebarOpen"
            class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Mobile Sidebar -->
        <aside
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                'fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:hidden'
            ]"
        >
            <div class="flex h-16 items-center justify-between px-6 border-b border-slate-200">
                <span class="text-xl font-bold text-brand-600">Silver Academy</span>
                <button @click="sidebarOpen = false" class="text-slate-500 hover:text-slate-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="px-4 py-6">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-brand-50 hover:text-brand-600 rounded-lg transition-colors mb-1"
                >
                    <span class="text-sm font-medium">{{ item.name }}</span>
                </Link>
            </nav>
        </aside>

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white border-r border-slate-200 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <Link href="/" class="text-xl font-bold text-brand-600">
                        Silver Academy
                    </Link>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="item in navigation" :key="item.name">
                                    <Link
                                        :href="item.href"
                                        class="group flex items-center gap-x-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition-colors"
                                    >
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <button
                                @click="logout"
                                class="group -mx-2 flex w-full items-center gap-x-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors"
                            >
                                Log out
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-72">
            <!-- Top Header -->
            <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button
                    type="button"
                    class="-m-2.5 p-2.5 text-slate-700 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1"></div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Profile dropdown -->
                        <div class="flex items-center gap-x-3">
                            <div class="h-8 w-8 rounded-full bg-brand-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ user?.name?.charAt(0) || 'U' }}
                                </span>
                            </div>
                            <span class="hidden lg:block text-sm font-medium text-slate-700">
                                {{ user?.name || 'User' }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-8 px-4 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>


