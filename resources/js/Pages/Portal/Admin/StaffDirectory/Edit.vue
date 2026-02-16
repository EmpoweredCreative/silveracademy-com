<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';

const props = defineProps({
    entry: Object,
});

const form = useForm({
    name: props.entry.name,
    title: props.entry.title,
    department: props.entry.department,
    photo: props.entry.photo || '',
});
</script>

<template>
    <Head title="Edit staff directory entry" />

    <PortalLayout>
        <template #header>Edit staff directory entry</template>

        <div class="max-w-2xl">
            <form @submit.prevent="form.put(`/portal/admin/staff-directory/${entry.id}`)" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Department *</label>
                    <input
                        id="department"
                        v-model="form.department"
                        type="text"
                        required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    />
                    <p v-if="form.errors.department" class="mt-1 text-sm text-red-600">{{ form.errors.department }}</p>
                </div>
                <div>
                    <label for="photo" class="block text-sm font-medium text-slate-700 mb-1">Photo path (optional)</label>
                    <input
                        id="photo"
                        v-model="form.photo"
                        type="text"
                        placeholder="e.g. /img/graphics/staff/Jane Smith.png"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    />
                    <p v-if="form.errors.photo" class="mt-1 text-sm text-red-600">{{ form.errors.photo }}</p>
                </div>
                <div class="flex gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Update' }}
                    </button>
                    <Link
                        href="/portal/admin/staff-directory"
                        class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </PortalLayout>
</template>
