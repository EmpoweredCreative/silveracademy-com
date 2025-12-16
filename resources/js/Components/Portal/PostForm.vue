<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    form: Object,
    post: {
        type: Object,
        default: null,
    },
    mode: {
        type: String,
        default: 'create',
    },
});

const emit = defineEmits(['submit']);

const imagePreview = ref(props.post?.image_path ? `/storage/${props.post.image_path}` : null);
const dragActive = ref(false);

const isEvent = computed(() => props.form.type === 'event');

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        props.form.image = file;
        props.form.remove_image = false;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    dragActive.value = false;
    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        props.form.image = file;
        props.form.remove_image = false;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    props.form.image = null;
    props.form.remove_image = true;
    imagePreview.value = null;
};

// Clear event fields when switching to news
watch(() => props.form.type, (newType) => {
    if (newType === 'news') {
        props.form.event_start_date = '';
        props.form.event_end_date = '';
        props.form.button_text = '';
        props.form.button_url = '';
    }
});
</script>

<template>
    <form @submit.prevent="emit('submit')" class="space-y-6">
        <!-- Post Type -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Post Type</label>
            <div class="flex gap-4">
                <label
                    :class="[
                        'flex-1 flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-colors',
                        form.type === 'news'
                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                            : 'border-slate-200 hover:border-slate-300'
                    ]"
                >
                    <input
                        type="radio"
                        v-model="form.type"
                        value="news"
                        class="sr-only"
                    />
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="font-medium">News Article</span>
                </label>
                <label
                    :class="[
                        'flex-1 flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-colors',
                        form.type === 'event'
                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                            : 'border-slate-200 hover:border-slate-300'
                    ]"
                >
                    <input
                        type="radio"
                        v-model="form.type"
                        value="event"
                        class="sr-only"
                    />
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Event</span>
                </label>
            </div>
        </div>

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                Title <span class="text-red-500">*</span>
            </label>
            <input
                id="title"
                type="text"
                v-model="form.title"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                placeholder="Enter a title..."
                required
            />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                Content <span class="text-red-500">*</span>
            </label>
            <textarea
                id="content"
                v-model="form.content"
                rows="8"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                placeholder="Write your content here..."
                required
            ></textarea>
            <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
        </div>

        <!-- Featured Image -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Featured Image</label>
            <div
                v-if="!imagePreview"
                :class="[
                    'border-2 border-dashed rounded-lg p-8 text-center transition-colors',
                    dragActive ? 'border-brand-500 bg-brand-50' : 'border-slate-300 hover:border-slate-400'
                ]"
                @dragover.prevent="dragActive = true"
                @dragleave="dragActive = false"
                @drop="handleDrop"
            >
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm text-slate-600">
                    Drag and drop an image, or
                    <label class="text-brand-600 hover:text-brand-700 cursor-pointer">
                        browse
                        <input
                            type="file"
                            accept="image/*"
                            class="sr-only"
                            @change="handleImageChange"
                        />
                    </label>
                </p>
                <p class="mt-1 text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
            </div>
            <div v-else class="relative inline-block">
                <img
                    :src="imagePreview"
                    alt="Preview"
                    class="max-h-48 rounded-lg"
                />
                <button
                    type="button"
                    @click="removeImage"
                    class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
        </div>

        <!-- Event-specific fields -->
        <template v-if="isEvent">
            <div class="border-t border-slate-200 pt-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Event Details</h3>
                
                <!-- Event Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="event_start_date" class="block text-sm font-medium text-slate-700 mb-1">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="event_start_date"
                            type="datetime-local"
                            v-model="form.event_start_date"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            required
                        />
                        <p v-if="form.errors.event_start_date" class="mt-1 text-sm text-red-600">{{ form.errors.event_start_date }}</p>
                    </div>
                    <div>
                        <label for="event_end_date" class="block text-sm font-medium text-slate-700 mb-1">
                            End Date <span class="text-slate-400">(optional)</span>
                        </label>
                        <input
                            id="event_end_date"
                            type="datetime-local"
                            v-model="form.event_end_date"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        />
                        <p v-if="form.errors.event_end_date" class="mt-1 text-sm text-red-600">{{ form.errors.event_end_date }}</p>
                    </div>
                </div>

                <!-- Registration Button -->
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-slate-700 mb-3">Registration Button (optional)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="button_text" class="block text-sm font-medium text-slate-600 mb-1">
                                Button Text
                            </label>
                            <input
                                id="button_text"
                                type="text"
                                v-model="form.button_text"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                placeholder="e.g., Register Here"
                            />
                        </div>
                        <div>
                            <label for="button_url" class="block text-sm font-medium text-slate-600 mb-1">
                                Button Link
                            </label>
                            <input
                                id="button_url"
                                type="url"
                                v-model="form.button_url"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                placeholder="https://..."
                            />
                        </div>
                    </div>
                    <p v-if="form.errors.button_url" class="mt-1 text-sm text-red-600">{{ form.errors.button_url }}</p>
                </div>
            </div>
        </template>

        <!-- Publish Options -->
        <div class="border-t border-slate-200 pt-6">
            <div v-if="mode === 'create'" class="flex items-center">
                <input
                    id="publish_now"
                    type="checkbox"
                    v-model="form.publish_now"
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded"
                />
                <label for="publish_now" class="ml-2 text-sm text-slate-700">
                    Publish immediately
                </label>
            </div>
            <div v-else class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-700">Publication Status</p>
                    <p class="text-sm text-slate-500">
                        {{ form.published_at ? 'This post is published' : 'This post is a draft' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-200">
            <a
                href="/portal/posts"
                class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900"
            >
                Cancel
            </a>
            <button
                type="submit"
                :disabled="form.processing"
                class="px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <span v-if="form.processing">Saving...</span>
                <span v-else>{{ mode === 'create' ? 'Create Post' : 'Save Changes' }}</span>
            </button>
        </div>
    </form>
</template>

