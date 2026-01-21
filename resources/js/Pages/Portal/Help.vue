<script setup>
import { Head, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { 
    RocketLaunchIcon,
    CalendarIcon,
    UserGroupIcon,
    AcademicCapIcon,
    MegaphoneIcon,
    ClipboardDocumentListIcon,
    EyeIcon,
    PencilSquareIcon,
    NewspaperIcon,
    UserCircleIcon,
    QuestionMarkCircleIcon,
    InformationCircleIcon,
    MagnifyingGlassIcon,
    Bars3Icon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object,
    sections: Array,
    role: String,
    actualRole: String,
});

// Handle preview role changes from other pages
const handlePreviewRoleChanged = (event) => {
    if (props.actualRole === 'super_admin' && event.detail) {
        const newRole = event.detail;
        // Reload the page with the new preview parameter
        if (newRole === 'admin') {
            router.visit('/portal/help');
        } else {
            router.visit(`/portal/help?preview=${newRole}`);
        }
    }
};

// On mount, check if localStorage preview role matches current view
onMounted(() => {
    if (typeof window !== 'undefined' && props.actualRole === 'super_admin') {
        const storedPreview = localStorage.getItem('portal_preview_role');
        const currentPreview = new URLSearchParams(window.location.search).get('preview');
        
        // If stored preview doesn't match URL, redirect
        if (storedPreview && storedPreview !== 'admin' && storedPreview !== currentPreview) {
            router.visit(`/portal/help?preview=${storedPreview}`);
        } else if ((!storedPreview || storedPreview === 'admin') && currentPreview) {
            router.visit('/portal/help');
        }
    }
    
    // Listen for preview role changes
    window.addEventListener('preview-role-changed', handlePreviewRoleChanged);
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('preview-role-changed', handlePreviewRoleChanged);
    }
});

// Active section
const activeSection = ref(props.sections[0]?.id || null);

// Mobile sidebar toggle
const showMobileSidebar = ref(false);

// Search functionality
const searchQuery = ref('');

const filteredSections = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.sections;
    }
    
    const query = searchQuery.value.toLowerCase();
    
    return props.sections.filter(section => {
        const matchesSection = section.title.toLowerCase().includes(query);
        const matchesItems = section.items.some(item => 
            item.title.toLowerCase().includes(query) ||
            item.content.toLowerCase().includes(query)
        );
        return matchesSection || matchesItems;
    });
});

// Get active section data
const activeSectionData = computed(() => {
    return props.sections.find(s => s.id === activeSection.value) || props.sections[0];
});

// Filter items in active section based on search
const filteredActiveItems = computed(() => {
    if (!searchQuery.value.trim() || !activeSectionData.value) {
        return activeSectionData.value?.items || [];
    }
    
    const query = searchQuery.value.toLowerCase();
    return activeSectionData.value.items.filter(item =>
        item.title.toLowerCase().includes(query) ||
        item.content.toLowerCase().includes(query)
    );
});

// Select section
const selectSection = (sectionId) => {
    activeSection.value = sectionId;
    showMobileSidebar.value = false;
    
    // Scroll to top of content on mobile
    if (window.innerWidth < 1024) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

// Icon mapping
const getIcon = (iconName) => {
    const icons = {
        'rocket': RocketLaunchIcon,
        'calendar': CalendarIcon,
        'users': UserGroupIcon,
        'family': UserGroupIcon,
        'academic': AcademicCapIcon,
        'megaphone': MegaphoneIcon,
        'clipboard': ClipboardDocumentListIcon,
        'eye': EyeIcon,
        'pencil': PencilSquareIcon,
        'newspaper': NewspaperIcon,
        'user': UserCircleIcon,
        'question': QuestionMarkCircleIcon,
        'info': InformationCircleIcon,
        'utensils': ClipboardDocumentListIcon,
    };
    return icons[iconName] || QuestionMarkCircleIcon;
};

// Role display
const roleLabel = computed(() => {
    const labels = {
        'super_admin': 'Super Administrator',
        'admin': 'Administrator',
        'teacher': 'Staff Member',
        'parent': 'Parent',
    };
    return labels[props.role] || 'User';
});

const roleColor = computed(() => {
    const colors = {
        'super_admin': 'purple',
        'admin': 'blue',
        'teacher': 'emerald',
        'parent': 'brand',
    };
    return colors[props.role] || 'slate';
});

// Set first matching section as active when search changes
const updateActiveOnSearch = () => {
    if (filteredSections.value.length > 0 && !filteredSections.value.find(s => s.id === activeSection.value)) {
        activeSection.value = filteredSections.value[0].id;
    }
};

// Watch for search changes
import { watch } from 'vue';
watch(searchQuery, updateActiveOnSearch);
</script>

<template>
    <Head title="Help & Documentation" />

    <PortalLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <span>Help & Documentation</span>
                <!-- Mobile menu button -->
                <button
                    @click="showMobileSidebar = true"
                    class="lg:hidden p-2 text-slate-500 hover:text-slate-700 rounded-lg hover:bg-slate-100"
                >
                    <Bars3Icon class="w-6 h-6" />
                </button>
            </div>
        </template>

        <div class="flex gap-8">
            <!-- Mobile Sidebar Overlay -->
            <div 
                v-if="showMobileSidebar"
                class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
                @click="showMobileSidebar = false"
            ></div>

            <!-- Sidebar / Table of Contents -->
            <aside 
                :class="[
                    'w-72 flex-shrink-0',
                    // Mobile styles
                    'fixed inset-y-0 left-0 z-50 bg-white shadow-xl transform transition-transform duration-300 lg:relative lg:inset-auto lg:z-auto lg:shadow-none lg:transform-none',
                    showMobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
                ]"
            >
                <div class="h-full flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden lg:sticky lg:top-4">
                    <!-- Sidebar Header -->
                    <div class="px-4 py-4 border-b border-slate-200 bg-gradient-to-r from-brand-50 to-slate-50">
                        <div class="flex items-center justify-between lg:justify-start gap-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-brand-100 rounded-lg">
                                    <QuestionMarkCircleIcon class="w-5 h-5 text-brand-600" />
                                </div>
                                <div>
                                    <h2 class="font-semibold text-slate-900">Help Topics</h2>
                                    <span 
                                        :class="[
                                            'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                                            roleColor === 'purple' ? 'bg-purple-100 text-purple-700' : '',
                                            roleColor === 'blue' ? 'bg-blue-100 text-blue-700' : '',
                                            roleColor === 'emerald' ? 'bg-emerald-100 text-emerald-700' : '',
                                            roleColor === 'brand' ? 'bg-brand-100 text-brand-700' : '',
                                        ]"
                                    >
                                        {{ roleLabel }}
                                    </span>
                                </div>
                            </div>
                            <!-- Mobile close button -->
                            <button
                                @click="showMobileSidebar = false"
                                class="lg:hidden p-1 text-slate-400 hover:text-slate-600 rounded"
                            >
                                <XMarkIcon class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="mt-3 relative">
                            <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search..."
                                class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            />
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 overflow-y-auto py-2">
                        <ul class="space-y-1 px-2">
                            <li v-for="section in filteredSections" :key="section.id">
                                <button
                                    @click="selectSection(section.id)"
                                    :class="[
                                        'w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-left transition-colors',
                                        activeSection === section.id
                                            ? (roleColor === 'purple' ? 'bg-purple-100 text-purple-900' :
                                               roleColor === 'blue' ? 'bg-blue-100 text-blue-900' :
                                               roleColor === 'emerald' ? 'bg-emerald-100 text-emerald-900' :
                                               'bg-brand-100 text-brand-900')
                                            : 'text-slate-700 hover:bg-slate-100'
                                    ]"
                                >
                                    <component 
                                        :is="getIcon(section.icon)" 
                                        :class="[
                                            'w-5 h-5 flex-shrink-0',
                                            activeSection === section.id
                                                ? (roleColor === 'purple' ? 'text-purple-600' :
                                                   roleColor === 'blue' ? 'text-blue-600' :
                                                   roleColor === 'emerald' ? 'text-emerald-600' :
                                                   'text-brand-600')
                                                : 'text-slate-400'
                                        ]"
                                    />
                                    <span class="text-sm font-medium truncate">{{ section.title }}</span>
                                    <span 
                                        :class="[
                                            'ml-auto text-xs px-1.5 py-0.5 rounded-full',
                                            activeSection === section.id
                                                ? (roleColor === 'purple' ? 'bg-purple-200 text-purple-700' :
                                                   roleColor === 'blue' ? 'bg-blue-200 text-blue-700' :
                                                   roleColor === 'emerald' ? 'bg-emerald-200 text-emerald-700' :
                                                   'bg-brand-200 text-brand-700')
                                                : 'bg-slate-200 text-slate-600'
                                        ]"
                                    >
                                        {{ section.items.length }}
                                    </span>
                                </button>
                            </li>
                        </ul>

                        <!-- No results -->
                        <div v-if="filteredSections.length === 0" class="px-4 py-8 text-center">
                            <MagnifyingGlassIcon class="w-8 h-8 text-slate-300 mx-auto mb-2" />
                            <p class="text-sm text-slate-500">No topics found</p>
                        </div>
                    </nav>

                    <!-- Contact Footer -->
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                        <a 
                            href="/contact" 
                            target="_blank"
                            class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
                        >
                            <QuestionMarkCircleIcon class="w-4 h-4" />
                            Contact Support
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <!-- Active Section Content -->
                <div v-if="activeSectionData" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Section Header -->
                    <div 
                        :class="[
                            'px-6 py-5 border-b',
                            roleColor === 'purple' ? 'bg-gradient-to-r from-purple-50 to-purple-100/50 border-purple-200' : '',
                            roleColor === 'blue' ? 'bg-gradient-to-r from-blue-50 to-blue-100/50 border-blue-200' : '',
                            roleColor === 'emerald' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-emerald-200' : '',
                            roleColor === 'brand' ? 'bg-gradient-to-r from-brand-50 to-brand-100/50 border-brand-200' : '',
                        ]"
                    >
                        <div class="flex items-center gap-4">
                            <div 
                                :class="[
                                    'p-3 rounded-xl',
                                    roleColor === 'purple' ? 'bg-purple-100' : '',
                                    roleColor === 'blue' ? 'bg-blue-100' : '',
                                    roleColor === 'emerald' ? 'bg-emerald-100' : '',
                                    roleColor === 'brand' ? 'bg-brand-100' : '',
                                ]"
                            >
                                <component 
                                    :is="getIcon(activeSectionData.icon)" 
                                    :class="[
                                        'w-6 h-6',
                                        roleColor === 'purple' ? 'text-purple-600' : '',
                                        roleColor === 'blue' ? 'text-blue-600' : '',
                                        roleColor === 'emerald' ? 'text-emerald-600' : '',
                                        roleColor === 'brand' ? 'text-brand-600' : '',
                                    ]"
                                />
                            </div>
                            <div>
                                <h1 class="text-xl font-serif font-bold text-slate-900">
                                    {{ activeSectionData.title }}
                                </h1>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    {{ filteredActiveItems.length }} topic{{ filteredActiveItems.length !== 1 ? 's' : '' }} in this section
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section Items -->
                    <div class="divide-y divide-slate-100">
                        <div 
                            v-for="(item, index) in filteredActiveItems" 
                            :key="index"
                            class="p-6 hover:bg-slate-50/50 transition-colors"
                        >
                            <div class="flex items-start gap-4">
                                <div 
                                    :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0',
                                        roleColor === 'purple' ? 'bg-purple-100 text-purple-600' : '',
                                        roleColor === 'blue' ? 'bg-blue-100 text-blue-600' : '',
                                        roleColor === 'emerald' ? 'bg-emerald-100 text-emerald-600' : '',
                                        roleColor === 'brand' ? 'bg-brand-100 text-brand-600' : '',
                                    ]"
                                >
                                    {{ index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-2">
                                        {{ item.title }}
                                    </h3>
                                    <p class="text-slate-600 leading-relaxed">
                                        {{ item.content }}
                                    </p>
                                    <!-- Styled steps list -->
                                    <ul v-if="item.steps && item.steps.length > 0" class="mt-3 ml-1 space-y-2 border-l-2 border-slate-200 pl-4">
                                        <li 
                                            v-for="(step, stepIndex) in item.steps" 
                                            :key="stepIndex"
                                            class="flex items-start gap-2 relative"
                                        >
                                            <span 
                                                :class="[
                                                    'absolute -left-[21px] top-2 flex-shrink-0 w-2.5 h-2.5 rounded-full',
                                                    roleColor === 'purple' ? 'bg-purple-400' : '',
                                                    roleColor === 'blue' ? 'bg-blue-400' : '',
                                                    roleColor === 'emerald' ? 'bg-emerald-400' : '',
                                                    roleColor === 'brand' ? 'bg-brand-400' : '',
                                                ]"
                                            ></span>
                                            <span class="text-slate-600 leading-relaxed text-sm">{{ step }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- No results in section -->
                        <div v-if="filteredActiveItems.length === 0" class="p-12 text-center">
                            <MagnifyingGlassIcon class="w-12 h-12 text-slate-300 mx-auto mb-4" />
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">No Matching Topics</h3>
                            <p class="text-slate-500">
                                No topics in this section match "{{ searchQuery }}".
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Card -->
                <div class="mt-6 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">Need more help?</h3>
                            <p class="text-sm text-slate-600 mt-1">
                                Can't find what you're looking for? We're here to help.
                            </p>
                        </div>
                        <a 
                            href="/contact" 
                            target="_blank"
                            :class="[
                                'inline-flex items-center justify-center px-5 py-2.5 font-medium rounded-lg transition-colors',
                                roleColor === 'purple' ? 'bg-purple-600 text-white hover:bg-purple-700' : '',
                                roleColor === 'blue' ? 'bg-blue-600 text-white hover:bg-blue-700' : '',
                                roleColor === 'emerald' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : '',
                                roleColor === 'brand' ? 'bg-brand-600 text-white hover:bg-brand-700' : '',
                            ]"
                        >
                            Contact Silver Academy
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </PortalLayout>
</template>
