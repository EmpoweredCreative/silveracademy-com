<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref } from 'vue';
import { 
    ArrowUpTrayIcon,
    ArrowDownTrayIcon,
    ChevronLeftIcon,
    DocumentTextIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    grades: Array,
});

const form = useForm({
    file: null,
});

const fileInput = ref(null);
const fileName = ref('');
const isDragOver = ref(false);

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
        fileName.value = file.name;
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragOver.value = false;
    const file = event.dataTransfer.files[0];
    if (file && (file.name.endsWith('.xlsx') || file.name.endsWith('.xls') || file.name.endsWith('.csv'))) {
        form.file = file;
        fileName.value = file.name;
    }
};

const handleDragOver = (event) => {
    event.preventDefault();
    isDragOver.value = true;
};

const handleDragLeave = () => {
    isDragOver.value = false;
};

const clearFile = () => {
    form.file = null;
    fileName.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const submit = () => {
    form.post('/portal/admin/classrooms/import', {
        forceFormData: true,
        onSuccess: () => {
            clearFile();
        },
    });
};
</script>

<template>
    <Head title="Import Students" />

    <PortalLayout>
        <template #header>Import Students</template>

        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Breadcrumb -->
            <div>
                <Link 
                    href="/portal/admin/classrooms"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    <ChevronLeftIcon class="w-4 h-4 mr-1" />
                    Back to Classroom Management
                </Link>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <InformationCircleIcon class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 mb-1">How It Works</h3>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li>Download the template and fill in student information</li>
                            <li>Each row represents one student assignment</li>
                            <li>Classrooms will be created automatically if they don't exist</li>
                            <li>Students will be matched by name within the same grade</li>
                            <li>Teachers are matched by email address</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Template Download -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-emerald-50 rounded-lg">
                        <DocumentTextIcon class="w-6 h-6 text-emerald-600" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-slate-900">Excel Template</h3>
                        <p class="text-sm text-slate-600 mt-1">
                            Download our pre-formatted template with sample data and reference sheets for grades and teachers.
                        </p>
                        <p class="text-xs text-slate-500 mt-2">
                            <strong>Required columns:</strong> Student Name, Grade, Classroom Name, Teacher Email
                        </p>
                    </div>
                    <a 
                        href="/portal/admin/classrooms/template"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors"
                    >
                        <ArrowDownTrayIcon class="w-4 h-4 mr-2" />
                        Download Template
                    </a>
                </div>
            </div>

            <!-- Upload Form -->
            <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Upload Students File</h3>

                <!-- File Drop Zone -->
                <div 
                    :class="[
                        'border-2 border-dashed rounded-lg p-8 text-center transition-colors',
                        isDragOver ? 'border-brand-500 bg-brand-50' : 'border-slate-300 hover:border-slate-400',
                        fileName ? 'bg-emerald-50 border-emerald-300' : ''
                    ]"
                    @drop="handleDrop"
                    @dragover="handleDragOver"
                    @dragleave="handleDragLeave"
                >
                    <template v-if="fileName">
                        <CheckCircleIcon class="mx-auto h-12 w-12 text-emerald-500" />
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ fileName }}</p>
                        <button 
                            type="button"
                            @click="clearFile"
                            class="mt-2 text-sm text-slate-600 hover:text-slate-900"
                        >
                            Choose a different file
                        </button>
                    </template>
                    <template v-else>
                        <ArrowUpTrayIcon class="mx-auto h-12 w-12 text-slate-400" />
                        <p class="mt-2 text-sm text-slate-600">
                            Drag and drop your Excel file here, or
                            <label class="text-brand-600 hover:text-brand-700 cursor-pointer">
                                browse
                                <input 
                                    ref="fileInput"
                                    type="file"
                                    accept=".xlsx,.xls,.csv"
                                    class="sr-only"
                                    @change="handleFileSelect"
                                />
                            </label>
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            Supported formats: .xlsx, .xls, .csv (max 10MB)
                        </p>
                    </template>
                </div>

                <!-- Error Display -->
                <div v-if="form.errors.file" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2">
                        <ExclamationTriangleIcon class="w-5 h-5 text-red-600" />
                        <p class="text-sm text-red-700">{{ form.errors.file }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex items-center justify-end gap-4">
                    <Link 
                        href="/portal/admin/classrooms"
                        class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="!form.file || form.processing"
                        class="inline-flex items-center px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <template v-if="form.processing">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Importing...
                        </template>
                        <template v-else>
                            <ArrowUpTrayIcon class="w-4 h-4 mr-2" />
                            Import Students
                        </template>
                    </button>
                </div>
            </form>

            <!-- Column Reference -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Column Reference</h3>
                </div>
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Column</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Required</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">Student Name</td>
                            <td class="px-6 py-4 text-sm text-red-600 font-medium">Yes</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Full name of the student</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">Grade</td>
                            <td class="px-6 py-4 text-sm text-red-600 font-medium">Yes</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Must match an existing grade level (e.g., "Kindergarten")</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">Classroom Name</td>
                            <td class="px-6 py-4 text-sm text-red-600 font-medium">Yes</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Will be created if it doesn't exist (e.g., "Room A")</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">Teacher Email</td>
                            <td class="px-6 py-4 text-sm text-slate-500">No</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Email of the teacher to assign to the classroom</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Available Grades Reference -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Available Grades</h3>
                    <p class="text-sm text-slate-500 mt-1">Use these exact names in your spreadsheet</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        <span 
                            v-for="grade in grades" 
                            :key="grade.id"
                            class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 text-sm rounded-lg"
                        >
                            {{ grade.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
