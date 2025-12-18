<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { Menu, MenuButton, MenuItem, MenuItems, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import {
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ClockIcon,
    EllipsisHorizontalIcon,
} from '@heroicons/vue/20/solid';
import { CalendarIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { ref, computed } from 'vue';

const props = defineProps({
    events: {
        type: Array,
        default: () => [],
    },
    lunchMenus: {
        type: Array,
        default: () => [],
    },
});

// Current date tracking
const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const currentMonthName = computed(() => {
    return `${monthNames[currentMonth.value]} ${currentYear.value}`;
});

// Navigate months
const previousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

const goToToday = () => {
    currentMonth.value = today.getMonth();
    currentYear.value = today.getFullYear();
};

// Generate calendar days
const days = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    
    // First day of month
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    
    // Start from Monday (adjust for week starting on Monday)
    let startDate = new Date(firstDay);
    const dayOfWeek = startDate.getDay();
    const daysToSubtract = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
    startDate.setDate(startDate.getDate() - daysToSubtract);
    
    const calendarDays = [];
    const todayStr = formatDate(today);
    
    // Generate 42 days (6 weeks)
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const dateStr = formatDate(date);
        const isCurrentMonth = date.getMonth() === month;
        const isToday = dateStr === todayStr;
        
        // Get events for this day
        const dayEvents = getEventsForDate(dateStr);
        
        calendarDays.push({
            date: dateStr,
            isCurrentMonth,
            isToday,
            events: dayEvents,
        });
    }
    
    return calendarDays;
});

// Format date as YYYY-MM-DD
const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

// Get events for a specific date
const getEventsForDate = (dateStr) => {
    const dayEvents = [];
    
    // Add calendar events
    props.events.forEach(event => {
        const eventDate = event.event_date?.split('T')[0];
        if (eventDate === dateStr) {
            dayEvents.push({
                id: `event-${event.id}`,
                name: event.title,
                time: formatTime(event.event_date),
                datetime: event.event_date,
                href: '#',
                type: 'event',
                isRecurring: event.is_recurring || false,
                recurrenceType: event.recurrence_type || 'none',
            });
        }
    });
    
    // Add lunch menus
    props.lunchMenus.forEach(menu => {
        if (menu.week_start === dateStr) {
            dayEvents.push({
                id: `lunch-${menu.id}`,
                name: 'Lunch Menu',
                time: 'Week of',
                datetime: menu.week_start,
                href: '/portal/lunch',
                type: 'lunch',
                isRecurring: false,
            });
        }
    });
    
    return dayEvents;
};

// Format time from datetime
const formatTime = (datetime) => {
    if (!datetime) return '';
    const date = new Date(datetime);
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
};

// Selected day events (for mobile)
const selectedDate = ref(formatDate(today));
const selectedDayEvents = computed(() => {
    const day = days.value.find(d => d.date === selectedDate.value);
    return day?.events || [];
});

const selectDay = (date) => {
    selectedDate.value = date;
};

// Get events for the current week (Monday-Sunday of selected week)
const currentWeekEvents = computed(() => {
    const weekEvents = [];
    
    // Find the Monday of the currently selected date's week
    const selectedDateObj = new Date(selectedDate.value + 'T00:00:00');
    const dayOfWeek = selectedDateObj.getDay();
    const mondayOffset = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
    const monday = new Date(selectedDateObj);
    monday.setDate(selectedDateObj.getDate() + mondayOffset);
    
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    
    // Filter events within this week
    props.events.forEach(event => {
        const eventDate = new Date(event.event_date);
        if (eventDate >= monday && eventDate <= sunday) {
            weekEvents.push({
                id: `event-${event.id}`,
                name: event.title,
                time: formatTime(event.event_date),
                datetime: event.event_date,
                dayName: eventDate.toLocaleDateString('en-US', { weekday: 'short' }),
                formattedDate: eventDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }),
                type: 'event',
                description: event.description || '',
                buttonText: event.button_text || '',
                buttonUrl: event.button_url || '',
                endDate: event.event_end_date,
                isRecurring: event.is_recurring || false,
                recurrenceType: event.recurrence_type || 'none',
            });
        }
    });
    
    // Add lunch menus for this week
    props.lunchMenus.forEach(menu => {
        const menuDate = new Date(menu.week_start + 'T00:00:00');
        if (menuDate >= monday && menuDate <= sunday) {
            weekEvents.push({
                id: `lunch-${menu.id}`,
                name: 'Lunch Menu',
                time: 'All Week',
                datetime: menu.week_start,
                dayName: 'Mon',
                formattedDate: menuDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }),
                type: 'lunch',
                description: menu.content || '',
                href: '/portal/lunch',
            });
        }
    });
    
    // Sort by date
    return weekEvents.sort((a, b) => new Date(a.datetime) - new Date(b.datetime));
});

// Get the week range for display
const selectedWeekRange = computed(() => {
    const selectedDateObj = new Date(selectedDate.value + 'T00:00:00');
    const dayOfWeek = selectedDateObj.getDay();
    const mondayOffset = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
    const monday = new Date(selectedDateObj);
    monday.setDate(selectedDateObj.getDate() + mondayOffset);
    
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    
    const monStr = monday.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    const sunStr = sunday.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    
    return `${monStr} - ${sunStr}`;
});

// Modal state
const showEventModal = ref(false);
const selectedEvent = ref(null);

const openEventDetails = (event) => {
    selectedEvent.value = event;
    showEventModal.value = true;
};

const closeEventModal = () => {
    showEventModal.value = false;
    selectedEvent.value = null;
};

const formatFullDate = (datetime) => {
    if (!datetime) return '';
    const date = new Date(datetime);
    return date.toLocaleDateString('en-US', { 
        weekday: 'long',
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Calendar" />

    <PortalLayout>
        <template #header>Calendar</template>
        
        <div class="lg:flex lg:h-full lg:flex-col">
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-4 lg:flex-none rounded-t-xl">
                <h1 class="text-base font-semibold text-slate-900">
                    <time :datetime="`${currentYear}-${String(currentMonth + 1).padStart(2, '0')}`">{{ currentMonthName }}</time>
                </h1>
                <div class="flex items-center">
                    <div class="relative flex items-center rounded-md bg-white shadow-sm outline outline-1 -outline-offset-1 outline-slate-300 md:items-stretch">
                        <button 
                            type="button" 
                            @click="previousMonth"
                            class="flex h-9 w-12 items-center justify-center rounded-l-md pr-1 text-slate-400 hover:text-slate-500 focus:relative md:w-9 md:pr-0 md:hover:bg-slate-50"
                        >
                            <span class="sr-only">Previous month</span>
                            <ChevronLeftIcon class="size-5" aria-hidden="true" />
                        </button>
                        <button 
                            type="button" 
                            @click="goToToday"
                            class="hidden px-3.5 text-sm font-semibold text-slate-900 hover:bg-slate-50 focus:relative md:block"
                        >
                            Today
                        </button>
                        <span class="relative -mx-px h-5 w-px bg-slate-300 md:hidden"></span>
                        <button 
                            type="button" 
                            @click="nextMonth"
                            class="flex h-9 w-12 items-center justify-center rounded-r-md pl-1 text-slate-400 hover:text-slate-500 focus:relative md:w-9 md:pl-0 md:hover:bg-slate-50"
                        >
                            <span class="sr-only">Next month</span>
                            <ChevronRightIcon class="size-5" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="hidden md:ml-4 md:flex md:items-center">
                        <div class="ml-6 h-6 w-px bg-slate-300"></div>
                        <Link 
                            href="/portal/posts/create" 
                            class="ml-6 rounded-md bg-brand-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
                        >
                            Add event
                        </Link>
                    </div>
                    <Menu as="div" class="relative ml-6 md:hidden">
                        <MenuButton class="-mx-2 flex items-center rounded-full border border-transparent p-2 text-slate-400 hover:text-slate-500">
                            <span class="sr-only">Open menu</span>
                            <EllipsisHorizontalIcon class="size-5" aria-hidden="true" />
                        </MenuButton>

                        <transition 
                            enter-active-class="transition ease-out duration-100" 
                            enter-from-class="transform opacity-0 scale-95" 
                            enter-to-class="transform scale-100" 
                            leave-active-class="transition ease-in duration-75" 
                            leave-from-class="transform scale-100" 
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems class="absolute right-0 z-10 mt-3 w-36 origin-top-right divide-y divide-slate-100 overflow-hidden rounded-md bg-white shadow-lg outline outline-1 outline-black/5">
                                <div class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <Link 
                                            href="/portal/posts/create" 
                                            :class="[active ? 'bg-slate-100 text-slate-900 outline-hidden' : 'text-slate-700', 'block px-4 py-2 text-sm']"
                                        >
                                            Create event
                                        </Link>
                                    </MenuItem>
                                </div>
                                <div class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <button 
                                            @click="goToToday"
                                            :class="[active ? 'bg-slate-100 text-slate-900 outline-hidden' : 'text-slate-700', 'block w-full text-left px-4 py-2 text-sm']"
                                        >
                                            Go to today
                                        </button>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </header>
            
            <div class="shadow-sm ring-1 ring-black/5 lg:flex lg:flex-auto lg:flex-col rounded-b-xl overflow-hidden">
                <!-- Day headers -->
                <div class="grid grid-cols-7 gap-px border-b border-slate-300 bg-slate-200 text-center text-xs/6 font-semibold text-slate-700 lg:flex-none">
                    <div class="flex justify-center bg-white py-2">
                        <span>M</span>
                        <span class="sr-only sm:not-sr-only">on</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>T</span>
                        <span class="sr-only sm:not-sr-only">ue</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>W</span>
                        <span class="sr-only sm:not-sr-only">ed</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>T</span>
                        <span class="sr-only sm:not-sr-only">hu</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>F</span>
                        <span class="sr-only sm:not-sr-only">ri</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>S</span>
                        <span class="sr-only sm:not-sr-only">at</span>
                    </div>
                    <div class="flex justify-center bg-white py-2">
                        <span>S</span>
                        <span class="sr-only sm:not-sr-only">un</span>
                    </div>
                </div>
                
                <!-- Calendar grid -->
                <div class="flex bg-slate-200 text-xs/6 text-slate-700 lg:flex-auto">
                    <!-- Desktop view -->
                    <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                        <div 
                            v-for="day in days" 
                            :key="day.date" 
                            :class="[
                                'group relative px-3 py-2 min-h-[100px]',
                                day.isCurrentMonth ? 'bg-white' : 'bg-slate-50 text-slate-500'
                            ]"
                        >
                            <time 
                                :datetime="day.date" 
                                :class="[
                                    day.isToday ? 'flex size-6 items-center justify-center rounded-full bg-brand-600 font-semibold text-white' : '',
                                    !day.isCurrentMonth ? 'opacity-75' : ''
                                ]"
                            >
                                {{ day.date.split('-').pop().replace(/^0/, '') }}
                            </time>
                            <ol v-if="day.events.length > 0" class="mt-2">
                                <li v-for="event in day.events.slice(0, 2)" :key="event.id">
                                    <a 
                                        :href="event.href" 
                                        class="group flex items-center"
                                    >
                                        <!-- Recurring indicator -->
                                        <svg 
                                            v-if="event.isRecurring"
                                            class="w-3 h-3 mr-1 flex-shrink-0 text-amber-500" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                            :title="`Repeats ${event.recurrenceType}`"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <p 
                                            :class="[
                                                'flex-auto truncate font-medium group-hover:text-brand-600',
                                                event.type === 'lunch' ? 'text-amber-600' : 'text-slate-900'
                                            ]"
                                        >
                                            {{ event.name }}
                                        </p>
                                        <time 
                                            :datetime="event.datetime" 
                                            class="ml-3 hidden flex-none text-slate-500 group-hover:text-brand-600 xl:block"
                                        >
                                            {{ event.time }}
                                        </time>
                                    </a>
                                </li>
                                <li v-if="day.events.length > 2" class="text-slate-500">
                                    + {{ day.events.length - 2 }} more
                                </li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Mobile view -->
                    <div class="isolate grid w-full grid-cols-7 grid-rows-6 gap-px lg:hidden">
                        <button 
                            v-for="day in days" 
                            :key="day.date" 
                            type="button" 
                            @click="selectDay(day.date)"
                            :class="[
                                'group relative flex h-14 flex-col px-3 py-2 hover:bg-slate-100 focus:z-10',
                                day.isCurrentMonth ? 'bg-white' : 'bg-slate-50',
                                selectedDate === day.date ? 'font-semibold' : '',
                                day.isToday && selectedDate !== day.date ? 'text-brand-600 font-semibold' : '',
                                !day.isCurrentMonth && !day.isToday ? 'text-slate-500' : 'text-slate-900'
                            ]"
                        >
                            <time 
                                :datetime="day.date" 
                                :class="[
                                    'ml-auto',
                                    !day.isCurrentMonth ? 'opacity-75' : '',
                                    selectedDate === day.date && !day.isToday ? 'flex size-6 items-center justify-center rounded-full bg-slate-900 text-white' : '',
                                    selectedDate === day.date && day.isToday ? 'flex size-6 items-center justify-center rounded-full bg-brand-600 text-white' : ''
                                ]"
                            >
                                {{ day.date.split('-').pop().replace(/^0/, '') }}
                            </time>
                            <span class="sr-only">{{ day.events.length }} events</span>
                            <span v-if="day.events.length > 0" class="-mx-0.5 mt-auto flex flex-wrap-reverse">
                                <span 
                                    v-for="event in day.events" 
                                    :key="event.id" 
                                    :class="[
                                        'mx-0.5 mb-1 size-1.5 rounded-full',
                                        event.type === 'lunch' ? 'bg-amber-400' : 'bg-slate-400'
                                    ]"
                                ></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile event list -->
            <div v-if="selectedDayEvents.length > 0" class="relative px-4 py-10 sm:px-6 lg:hidden">
                <ol class="divide-y divide-slate-100 overflow-hidden rounded-lg bg-white text-sm shadow-sm outline outline-1 outline-black/5">
                    <li 
                        v-for="event in selectedDayEvents" 
                        :key="event.id" 
                        class="group flex p-4 pr-6 focus-within:bg-slate-50 hover:bg-slate-50"
                    >
                        <div class="flex-auto">
                            <p 
                                :class="[
                                    'font-semibold',
                                    event.type === 'lunch' ? 'text-amber-600' : 'text-slate-900'
                                ]"
                            >
                                {{ event.name }}
                            </p>
                            <time :datetime="event.datetime" class="mt-2 flex items-center text-slate-700">
                                <ClockIcon class="mr-2 size-5 text-slate-400" aria-hidden="true" />
                                {{ event.time }}
                            </time>
                        </div>
                        <a 
                            :href="event.href" 
                            class="ml-6 flex-none self-center rounded-md bg-white px-3 py-2 font-semibold text-slate-900 opacity-0 shadow-sm ring-1 ring-slate-300 ring-inset group-hover:opacity-100 hover:ring-slate-400 focus:opacity-100"
                        >
                            View<span class="sr-only">, {{ event.name }}</span>
                        </a>
                    </li>
                </ol>
            </div>
            
            <!-- Empty state for mobile -->
            <div v-else class="px-4 py-10 sm:px-6 lg:hidden">
                <p class="text-center text-sm text-slate-500">No events on selected day</p>
            </div>
            
            <!-- Weekly Events List -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-serif font-semibold text-slate-900">Events This Week</h2>
                        <span class="text-sm text-slate-500">{{ selectedWeekRange }}</span>
                    </div>
                </div>
                
                <div v-if="currentWeekEvents.length > 0" class="divide-y divide-slate-100">
                    <div 
                        v-for="event in currentWeekEvents" 
                        :key="event.id"
                        class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors"
                    >
                        <div class="flex-shrink-0">
                            <span class="text-sm font-medium text-slate-500">{{ event.dayName }}</span>
                            <span class="text-sm font-semibold text-slate-900 ml-1">{{ event.formattedDate }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p :class="[
                                    'font-medium',
                                    event.type === 'lunch' ? 'text-amber-600' : 'text-slate-900'
                                ]">
                                    {{ event.name }}
                                </p>
                                <!-- Recurring indicator -->
                                <span 
                                    v-if="event.isRecurring"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700"
                                    :title="`Repeats ${event.recurrenceType}`"
                                >
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ event.recurrenceType === 'daily' ? 'Daily' : event.recurrenceType === 'weekly' ? 'Weekly' : event.recurrenceType === 'biweekly' ? 'Bi-weekly' : 'Monthly' }}
                                </span>
                            </div>
                        </div>
                        <button 
                            type="button"
                            @click="openEventDetails(event)"
                            class="flex-shrink-0 px-4 py-2 text-sm font-medium text-brand-600 bg-brand-50 rounded-lg hover:bg-brand-100 transition-colors"
                        >
                            View Details
                        </button>
                    </div>
                </div>
                
                <div v-else class="px-6 py-12 text-center">
                    <CalendarIcon class="mx-auto h-12 w-12 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No events scheduled for this week.</p>
                </div>
            </div>
        </div>
        
        <!-- Event Details Modal -->
        <TransitionRoot as="template" :show="showEventModal">
            <Dialog class="relative z-50" @close="closeEventModal">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-slate-500/75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <TransitionChild
                            as="template"
                            enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <DialogPanel class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <!-- Header -->
                                <div class="bg-brand-600 px-6 py-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <DialogTitle class="text-lg font-serif font-semibold text-white">
                                                {{ selectedEvent?.name }}
                                            </DialogTitle>
                                            <p class="mt-1 text-sm text-brand-100">
                                                {{ formatFullDate(selectedEvent?.datetime) }}
                                                <span v-if="selectedEvent?.time && selectedEvent?.time !== 'All Week'">
                                                    at {{ selectedEvent?.time }}
                                                </span>
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            class="rounded-md text-brand-200 hover:text-white focus:outline-none"
                                            @click="closeEventModal"
                                        >
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="px-6 py-5">
                                    <!-- Event Type & Recurrence Badges -->
                                    <div class="mb-4 flex items-center gap-2 flex-wrap">
                                        <span 
                                            :class="[
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                selectedEvent?.type === 'lunch' 
                                                    ? 'bg-amber-100 text-amber-800' 
                                                    : 'bg-brand-100 text-brand-800'
                                            ]"
                                        >
                                            {{ selectedEvent?.type === 'lunch' ? 'Lunch Menu' : 'Event' }}
                                        </span>
                                        <!-- Recurrence Badge -->
                                        <span 
                                            v-if="selectedEvent?.isRecurring"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700"
                                        >
                                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Repeats {{ selectedEvent?.recurrenceType === 'daily' ? 'Daily' : selectedEvent?.recurrenceType === 'weekly' ? 'Weekly' : selectedEvent?.recurrenceType === 'biweekly' ? 'Bi-weekly' : 'Monthly' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div v-if="selectedEvent?.description" class="prose prose-sm max-w-none text-slate-700">
                                        <div v-html="selectedEvent.description"></div>
                                    </div>
                                    <p v-else class="text-sm text-slate-500 italic">
                                        No additional details available for this event.
                                    </p>
                                    
                                    <!-- End Date if different -->
                                    <div v-if="selectedEvent?.endDate && selectedEvent?.type !== 'lunch'" class="mt-4 p-3 bg-slate-50 rounded-lg">
                                        <p class="text-sm text-slate-600">
                                            <span class="font-medium">Ends:</span> {{ formatFullDate(selectedEvent.endDate) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Footer with Actions -->
                                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row gap-3 sm:justify-end">
                                    <!-- Registration/Action Button -->
                                    <a 
                                        v-if="selectedEvent?.buttonUrl"
                                        :href="selectedEvent.buttonUrl"
                                        target="_blank"
                                        class="inline-flex justify-center items-center px-4 py-2 bg-accent-500 text-white font-semibold rounded-lg hover:bg-accent-600 transition-colors"
                                    >
                                        {{ selectedEvent.buttonText || 'Register Here' }}
                                    </a>
                                    
                                    <!-- Lunch Menu Link -->
                                    <Link 
                                        v-if="selectedEvent?.type === 'lunch'"
                                        href="/portal/lunch"
                                        class="inline-flex justify-center items-center px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition-colors"
                                    >
                                        View Full Menu
                                    </Link>
                                    
                                    <button
                                        type="button"
                                        class="inline-flex justify-center items-center px-4 py-2 bg-white text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors"
                                        @click="closeEventModal"
                                    >
                                        Close
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </PortalLayout>
</template>

