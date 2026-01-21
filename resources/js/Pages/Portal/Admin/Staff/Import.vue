<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed } from 'vue';
import { 
    ChevronLeftIcon,
    ArrowUpTrayIcon,
    DocumentArrowDownIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    DocumentTextIcon,
    InformationCircleIcon,
} from '@heroicons/vue/24/outline';

const page = usePage();

const form = useForm({
    file: null,
});

const fileInput = ref(null);
const dragOver = ref(false);

// Check for success message from flash
const importSuccess = computed(() => page.props.flash?.success);
const importError = computed(() => page.props.flash?.error);

const selectFile = () => {
    fileInput.value?.click();
};

const handleFileSelect = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        form.file = file;
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    dragOver.value = false;
    const file = event.dataTransfer.files?.[0];
    if (file) {
        form.file = file;
    }
};

const handleDragOver = (event) => {
    event.preventDefault();
    dragOver.value = true;
};

const handleDragLeave = () => {
    dragOver.value = false;
};

const clearFile = () => {
    form.file = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const submit = () => {
    form.post('/portal/admin/staff/import', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.file = null;
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};
</script>

<template>
    <Head title="Import Staff" />

    <PortalLayout>
        <template #header>Import Staff</template>

        <div class="space-y-6">
            <!-- Back Link -->
            <div>
                <Link 
                    href="/portal/admin/staff"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to Staff Management
                </Link>
            </div>

            <!-- Success Message -->
            <div v-if="importSuccess" class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <CheckCircleIcon class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-medium text-green-800">{{ importSuccess }}</p>
                        <p class="text-sm text-green-700 mt-1">
                            Go to <Link href="/portal/admin/staff" class="font-medium underline">Staff Management</Link> 
                            to review imported staff and send welcome emails.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="importError" class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <ExclamationTriangleIcon class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-medium text-red-800">{{ importError }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Import Form -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-900">Upload Staff List</h2>
                        <p class="text-sm text-slate-500 mt-1">Import multiple staff members from a CSV or Excel file</p>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- File Drop Zone -->
                            <div
                                @drop="handleDrop"
                                @dragover="handleDragOver"
                                @dragleave="handleDragLeave"
                                @click="selectFile"
                                :class="[
                                    'border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-colors',
                                    dragOver ? 'border-brand-500 bg-brand-50' : 'border-slate-300 hover:border-slate-400',
                                    form.errors.file ? 'border-red-500 bg-red-50' : '',
                                ]"
                            >
                                <input
                                    ref="fileInput"
                                    type="file"
                                    accept=".csv,.xlsx,.xls"
                                    @change="handleFileSelect"
                                    class="hidden"
                                />
                                
                                <div v-if="form.file">
                                    <DocumentTextIcon class="w-12 h-12 text-brand-500 mx-auto mb-3" />
                                    <p class="text-sm font-medium text-slate-900">{{ form.file.name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ (form.file.size / 1024).toFixed(1) }} KB
                                    </p>
                                    <button
                                        type="button"
                                        @click.stop="clearFile"
                                        class="mt-3 text-sm text-red-600 hover:text-red-700"
                                    >
                                        Remove file
                                    </button>
                                </div>
                                <div v-else>
                                    <ArrowUpTrayIcon class="w-12 h-12 text-slate-400 mx-auto mb-3" />
                                    <p class="text-sm text-slate-600">
                                        <span class="font-medium text-brand-600">Click to upload</span>
                                        or drag and drop
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">CSV or Excel file (max 10MB)</p>
                                </div>
                            </div>

                            <p v-if="form.errors.file" class="text-sm text-red-600">
                                {{ form.errors.file }}
                            </p>

                            <button
                                type="submit"
                                :disabled="!form.file || form.processing"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <ArrowUpTrayIcon class="w-5 h-5" />
                                {{ form.processing ? 'Importing...' : 'Import Staff' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="space-y-6">
                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <InformationCircleIcon class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                            <div>
                                <h3 class="text-sm font-medium text-blue-900 mb-1">Two-Step Process</h3>
                                <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                                    <li><strong>Import:</strong> Staff accounts are created (but cannot log in yet)</li>
                                    <li><strong>Send Emails:</strong> After reviewing, send welcome emails with login credentials</li>
                                </ol>
                                <p class="text-sm text-blue-700 mt-2">
                                    This allows you to verify the import was successful before notifying staff.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h2 class="font-semibold text-slate-900">File Format</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-slate-600">
                                Your file should include the following columns:
                            </p>
                            <div class="bg-slate-50 rounded-lg p-4 font-mono text-xs overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="text-slate-500">
                                            <th class="text-left pr-4">name</th>
                                            <th class="text-left pr-4">email</th>
                                            <th class="text-left">role</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-700">
                                        <tr>
                                            <td class="pr-4">John Smith</td>
                                            <td class="pr-4">jsmith@silveracademypa.org</td>
                                            <td>teacher</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4">Jane Doe</td>
                                            <td class="pr-4">jdoe@silveracademypa.org</td>
                                            <td>admin</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-sm text-slate-600 space-y-2">
                                <p><strong>Valid roles:</strong> teacher, admin</p>
                                <p class="text-xs text-slate-500">
                                    Staff accounts are created without passwords. Passwords are generated and 
                                    sent when you click "Send Welcome Emails" on the Staff Management page.
                                </p>
                            </div>
                            <a
                                href="/portal/admin/staff/template"
                                class="inline-flex items-center gap-2 text-sm text-brand-600 hover:text-brand-700"
                            >
                                <DocumentArrowDownIcon class="w-4 h-4" />
                                Download template file
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
