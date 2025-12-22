<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { CalendarIcon, PlusIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    currentMenu: {
        type: Object,
        default: null,
    },
    upcomingMenus: {
        type: Array,
        default: () => [],
    },
    pastMenus: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const user = page.props.auth?.user;

const isAdmin = computed(() => {
    return user?.role === 'admin' || user?.role === 'super_admin';
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { 
        weekday: 'long',
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
};

const formatWeekRange = (startDateStr) => {
    if (!startDateStr) return '';
    const start = new Date(startDateStr + 'T00:00:00');
    const end = new Date(start);
    end.setDate(end.getDate() + 4); // Monday to Friday
    
    const startMonth = start.toLocaleDateString('en-US', { month: 'short' });
    const endMonth = end.toLocaleDateString('en-US', { month: 'short' });
    const startDay = start.getDate();
    const endDay = end.getDate();
    
    if (startMonth === endMonth) {
        return `${startMonth} ${startDay} - ${endDay}`;
    }
    return `${startMonth} ${startDay} - ${endMonth} ${endDay}`;
};
</script>

<template>
    <Head title="Lunch Menu" />

    <PortalLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <span>Lunch Menu</span>
                
                <!-- Admin Add Button -->
                <Link 
                    v-if="isAdmin"
                    href="/portal/lunch/create"
                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-brand-600 text-sm font-medium text-white hover:bg-brand-700 transition-colors shadow-sm"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Add Menu
                </Link>
            </div>
        </template>
        
        <div class="space-y-8">
            <!-- Current Week's Menu -->
            <div v-if="currentMenu" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-brand-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-serif font-semibold text-brand-900">This Week's Menu</h2>
                            <p class="text-sm text-brand-700 mt-1">
                                Week of {{ formatDate(currentMenu.week_start) }}
                            </p>
                        </div>
                        <div class="p-2 bg-brand-100 rounded-lg">
                            <CalendarIcon class="w-6 h-6 text-brand-600" />
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-slate-700" v-html="currentMenu.content"></div>
                </div>
                <div v-if="isAdmin" class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <Link 
                        :href="`/portal/lunch/${currentMenu.id}/edit`"
                        class="text-sm font-medium text-brand-600 hover:text-brand-700"
                    >
                        Edit this menu →
                    </Link>
                </div>
            </div>
            
            <!-- No Current Menu -->
            <div v-else class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <CalendarIcon class="mx-auto h-12 w-12 text-slate-400" />
                <h3 class="mt-2 text-sm font-semibold text-slate-900">No menu for this week</h3>
                <p class="mt-1 text-sm text-slate-500">The lunch menu for this week hasn't been posted yet.</p>
                <div v-if="isAdmin" class="mt-6">
                    <Link
                        href="/portal/lunch/create"
                        class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors"
                    >
                        <PlusIcon class="w-5 h-5 mr-2" />
                        Add This Week's Menu
                    </Link>
                </div>
            </div>

            <!-- Upcoming Menus -->
            <div v-if="upcomingMenus.length > 0">
                <h2 class="text-lg font-serif font-semibold text-slate-900 mb-4">Upcoming Weeks</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div 
                        v-for="menu in upcomingMenus" 
                        :key="menu.id"
                        class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Upcoming
                            </span>
                            <span class="text-sm text-slate-500">{{ formatWeekRange(menu.week_start) }}</span>
                        </div>
                        <p class="text-sm text-slate-600 line-clamp-3">{{ menu.content.replace(/<[^>]*>/g, '').substring(0, 150) }}...</p>
                        <div v-if="isAdmin" class="mt-4 pt-4 border-t border-slate-200">
                            <Link 
                                :href="`/portal/lunch/${menu.id}/edit`"
                                class="text-sm font-medium text-brand-600 hover:text-brand-700"
                            >
                                Edit →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Past Menus (Admin Only) -->
            <div v-if="isAdmin && pastMenus.length > 0">
                <h2 class="text-lg font-serif font-semibold text-slate-900 mb-4">Past Menus</h2>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Week
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Posted By
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="menu in pastMenus" :key="menu.id" class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    {{ formatDate(menu.week_start) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ menu.user?.name || 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link 
                                        :href="`/portal/lunch/${menu.id}/edit`"
                                        class="text-brand-600 hover:text-brand-700"
                                    >
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>



