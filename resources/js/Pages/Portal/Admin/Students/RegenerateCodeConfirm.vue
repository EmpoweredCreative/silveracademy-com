<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { KeyIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    student: Object,
    regenerateUrl: String,
    redirectUrl: String,
    csrf_token: String,
});
</script>

<template>
    <Head title="Regenerate Parent Code" />

    <PortalLayout>
        <template #header>Regenerate Parent Code</template>

        <div class="space-y-6 max-w-md">
            <div>
                <Link
                    :href="redirectUrl"
                    class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900"
                >
                    ← Back
                </Link>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <KeyIcon class="w-6 h-6 text-amber-600" />
                    </div>
                    <h2 class="text-lg font-semibold text-slate-900">Regenerate code for {{ student?.name }}?</h2>
                </div>
                <p class="text-sm text-slate-600 mb-6">
                    The current parent code will stop working immediately. A new code will be generated. Share the new code only through a secure channel.
                </p>

                <!-- Plain form: works even if Vue/JS fails -->
                <form :action="regenerateUrl" method="POST" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="redirect_url" :value="redirectUrl" />
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition-colors"
                    >
                        Yes, regenerate code
                    </button>
                    <Link
                        :href="redirectUrl"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </Link>
                </form>
            </div>
        </div>
    </PortalLayout>
</template>
