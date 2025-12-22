<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = page.props.auth?.user

const isAdmin = computed(() => {
    return user?.role === 'admin' || user?.role === 'super_admin'
})

const navigation = computed(() => {
    const items = [
        { name: 'Dashboard', href: '/portal/dashboard' },
        { name: 'Lunch Menu', href: '/portal/lunch' },
        { name: 'Calendar', href: '/portal/calendar' },
    ]

    if (isAdmin.value) {
        items.push({ name: 'News & Events', href: '/portal/posts' })
        items.push({ name: 'Staff', href: '/portal/admin/staff' })
        items.push({ name: 'Classrooms', href: '/portal/admin/classrooms' })
    }

    items.push({ name: 'Settings', href: '/portal/settings' })

    return items
})

const userNavigation = [
    { name: 'Your profile', href: '/portal/profile' },
    { name: 'Settings', href: '/portal/settings' },
]

const logout = () => {
    router.post('/logout')
}

// Get user initials for avatar
const userInitials = computed(() => {
    if (!user?.name) return 'U'
    return user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

// Check if route is active
const isActive = (href) => {
    return page.url === href || page.url.startsWith(href + '/')
}
</script>

<template>
    <div class="min-h-full bg-slate-50">
        <Disclosure as="nav" class="border-b border-slate-200 bg-white dark:border-white/10 dark:bg-slate-800/50" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link href="/">
                                <img 
                                    class="h-8 w-auto" 
                                    src="/img/logo/silveracademylogo.png" 
                                    alt="Silver Academy" 
                                />
                            </Link>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <Link 
                                v-for="item in navigation" 
                                :key="item.name" 
                                :href="item.href" 
                                :class="[
                                    isActive(item.href)
                                        ? 'border-brand-600 text-slate-900 dark:border-brand-500 dark:text-white' 
                                        : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:border-white/20 dark:hover:text-slate-200', 
                                    'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium'
                                ]"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <button 
                            type="button" 
                            class="relative rounded-full p-1 text-slate-400 hover:text-slate-500 focus:outline-2 focus:outline-offset-2 focus:outline-brand-600 dark:text-slate-400 dark:hover:text-white dark:focus:outline-brand-500"
                        >
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="size-6" aria-hidden="true" />
                        </button>

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <MenuButton class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 dark:focus-visible:outline-brand-500">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <div class="size-8 rounded-full bg-brand-600 flex items-center justify-center outline -outline-offset-1 outline-black/5 dark:outline-white/10">
                                    <span class="text-sm font-medium text-white">{{ userInitials }}</span>
                                </div>
                            </MenuButton>

                            <transition 
                                enter-active-class="transition ease-out duration-200" 
                                enter-from-class="transform opacity-0 scale-95" 
                                enter-to-class="transform scale-100" 
                                leave-active-class="transition ease-in duration-75" 
                                leave-from-class="transform scale-100" 
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg outline outline-black/5 dark:bg-slate-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
                                    <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                        <Link 
                                            :href="item.href" 
                                            :class="[
                                                active ? 'bg-slate-100 outline-hidden dark:bg-slate-700' : '', 
                                                'block px-4 py-2 text-sm text-slate-700 dark:text-slate-300'
                                            ]"
                                        >
                                            {{ item.name }}
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <button
                                            @click="logout"
                                            :class="[
                                                active ? 'bg-slate-100 outline-hidden dark:bg-slate-700' : '', 
                                                'block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300'
                                            ]"
                                        >
                                            Sign out
                                        </button>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500 focus:outline-2 focus:outline-offset-2 focus:outline-brand-600 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white dark:focus:outline-brand-500">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="sm:hidden">
                <div class="space-y-1 pt-2 pb-3">
                    <DisclosureButton 
                        v-for="item in navigation" 
                        :key="item.name" 
                        as="template"
                    >
                        <Link 
                            :href="item.href" 
                            :class="[
                                isActive(item.href)
                                    ? 'border-brand-600 bg-brand-50 text-brand-700 dark:border-brand-500 dark:bg-brand-600/10 dark:text-brand-300' 
                                    : 'border-transparent text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 dark:text-slate-400 dark:hover:border-white/20 dark:hover:bg-white/5 dark:hover:text-slate-200', 
                                'block border-l-4 py-2 pr-4 pl-3 text-base font-medium'
                            ]"
                        >
                            {{ item.name }}
                        </Link>
                    </DisclosureButton>
                </div>
                <div class="border-t border-slate-200 pt-4 pb-3 dark:border-slate-700">
                    <div class="flex items-center px-4">
                        <div class="shrink-0">
                            <div class="size-10 rounded-full bg-brand-600 flex items-center justify-center outline -outline-offset-1 outline-black/5 dark:outline-white/10">
                                <span class="text-sm font-medium text-white">{{ userInitials }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-slate-800 dark:text-white">{{ user?.name || 'User' }}</div>
                            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ user?.email || '' }}</div>
                        </div>
                        <button 
                            type="button" 
                            class="relative ml-auto shrink-0 rounded-full p-1 text-slate-400 hover:text-slate-500 focus:outline-2 focus:outline-offset-2 focus:outline-brand-600 dark:text-slate-400 dark:hover:text-white dark:focus:outline-brand-500"
                        >
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="size-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="mt-3 space-y-1">
                        <DisclosureButton 
                            v-for="item in userNavigation" 
                            :key="item.name" 
                            as="template"
                        >
                            <Link 
                                :href="item.href" 
                                class="block px-4 py-2 text-base font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-slate-200"
                            >
                                {{ item.name }}
                            </Link>
                        </DisclosureButton>
                        <DisclosureButton as="template">
                            <button
                                @click="logout"
                                class="block w-full text-left px-4 py-2 text-base font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-slate-200"
                            >
                                Sign out
                            </button>
                        </DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <div class="py-10 bg-white min-h-screen">
            <header v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-serif font-bold tracking-tight text-slate-900">
                        <slot name="header" />
                    </h1>
                </div>
            </header>
            <main>
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
