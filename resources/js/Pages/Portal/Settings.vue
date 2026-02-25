<script setup>
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { 
    UserCircleIcon,
    KeyIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    CameraIcon,
    TrashIcon,
    UserGroupIcon,
    PlusIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object,
    children: {
        type: Array,
        default: () => [],
    },
});

// Add Child (parents only)
const addChildCode = ref('');
const addChildSubmitting = ref(false);
const addChildError = ref('');
const addChildSuccess = ref('');

const addChild = async () => {
    const c = (addChildCode.value || '').trim();
    if (!c) {
        addChildError.value = 'Please enter a parent code.';
        return;
    }
    addChildError.value = '';
    addChildSuccess.value = '';
    addChildSubmitting.value = true;
    try {
        const { data } = await axios.post('/portal/parent/add-child', { code: c });
        addChildSuccess.value = data.message || 'Child added successfully.';
        addChildCode.value = '';
        router.reload({ preserveScroll: true });
    } catch (err) {
        addChildError.value = err.response?.data?.message || 'Invalid code or this student has reached the maximum number of linked accounts. Contact the school office.';
    } finally {
        addChildSubmitting.value = false;
    }
};

const page = usePage();
const user = computed(() => page.props.auth?.user ?? props.user);

// Show Linked Students when actually a parent, or when super_admin is previewing as parent
const storedPreviewRole = ref('admin');
const showLinkedStudents = computed(() => {
    const role = user.value?.role;
    if (role === 'parent') return true;
    if (role === 'super_admin' && storedPreviewRole.value === 'parent') return true;
    return false;
});
function updatePreviewRole() {
    const stored = localStorage.getItem('portal_preview_role');
    if (stored && ['admin', 'teacher', 'parent'].includes(stored)) storedPreviewRole.value = stored;
}
function onPreviewRoleChanged(event) {
    if (event?.detail && ['admin', 'teacher', 'parent'].includes(event.detail)) {
        storedPreviewRole.value = event.detail;
    } else {
        updatePreviewRole();
    }
}
onMounted(() => {
    updatePreviewRole();
    window.addEventListener('storage', updatePreviewRole);
    window.addEventListener('preview-role-changed', onPreviewRoleChanged);
});
onUnmounted(() => {
    window.removeEventListener('storage', updatePreviewRole);
    window.removeEventListener('preview-role-changed', onPreviewRoleChanged);
});

// Flash messages
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);

// Profile form
const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
});

const updateProfile = () => {
    profileForm.put('/portal/settings/profile', {
        preserveScroll: true,
    });
};

// Password form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    passwordForm.put('/portal/settings/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

// Avatar
const avatarInput = ref(null);
const avatarPreview = ref(null);
const avatarForm = useForm({
    avatar: null,
});

const selectAvatar = () => {
    avatarInput.value?.click();
};

const handleAvatarSelect = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        avatarForm.avatar = file;
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const uploadAvatar = () => {
    avatarForm.post('/portal/settings/avatar', {
        preserveScroll: true,
        onSuccess: () => {
            avatarForm.reset();
            avatarPreview.value = null;
            if (avatarInput.value) {
                avatarInput.value.value = '';
            }
            // Force reload to update shared auth data in navigation
            window.location.reload();
        },
    });
};

const removeAvatar = () => {
    if (confirm('Are you sure you want to remove your avatar?')) {
        avatarForm.delete('/portal/settings/avatar', {
            preserveScroll: true,
            onSuccess: () => {
                // Force reload to update shared auth data in navigation
                window.location.reload();
            },
        });
    }
};

const cancelAvatarUpload = () => {
    avatarForm.reset();
    avatarPreview.value = null;
    if (avatarInput.value) {
        avatarInput.value.value = '';
    }
};

// User initials for default avatar
const userInitials = computed(() => {
    if (!props.user?.name) return 'U';
    return props.user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

// Role label
const roleLabel = computed(() => {
    const labels = {
        'super_admin': 'Super Admin',
        'admin': 'Administrator',
        'teacher': 'Staff Member',
        'parent': 'Parent',
    };
    return labels[props.user?.role] || 'User';
});
</script>

<template>
    <Head title="Settings" />

    <PortalLayout>
        <template #header>Account Settings</template>

        <div class="max-w-3xl mx-auto space-y-8">
            <!-- Success/Error Messages -->
            <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <CheckCircleIcon class="w-5 h-5 text-green-600" />
                    <p class="text-sm text-green-800">{{ successMessage }}</p>
                </div>
            </div>
            <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <ExclamationTriangleIcon class="w-5 h-5 text-red-600" />
                    <p class="text-sm text-red-800">{{ errorMessage }}</p>
                </div>
            </div>

            <!-- Avatar Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Profile Picture</h2>
                    <p class="text-sm text-slate-500 mt-1">Update your profile picture</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        <!-- Current Avatar / Preview -->
                        <div class="relative">
                            <div v-if="avatarPreview" class="w-24 h-24 rounded-full overflow-hidden">
                                <img :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover" />
                            </div>
                            <div v-else-if="user.avatar_url" class="w-24 h-24 rounded-full overflow-hidden">
                                <img :src="user.avatar_url" alt="Current avatar" class="w-full h-full object-cover" />
                            </div>
                            <div v-else class="w-24 h-24 rounded-full bg-brand-600 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ userInitials }}</span>
                            </div>
                        </div>

                        <!-- Upload Controls -->
                        <div class="flex-1">
                            <input
                                ref="avatarInput"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                @change="handleAvatarSelect"
                                class="hidden"
                            />
                            
                            <div v-if="avatarPreview" class="flex items-center gap-3">
                                <button
                                    @click="uploadAvatar"
                                    :disabled="avatarForm.processing"
                                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50 transition-colors"
                                >
                                    {{ avatarForm.processing ? 'Uploading...' : 'Save Avatar' }}
                                </button>
                                <button
                                    @click="cancelAvatarUpload"
                                    class="px-4 py-2 text-slate-600 hover:text-slate-800 transition-colors"
                                >
                                    Cancel
                                </button>
                            </div>
                            <div v-else class="flex items-center gap-3">
                                <button
                                    @click="selectAvatar"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors"
                                >
                                    <CameraIcon class="w-4 h-4 mr-2" />
                                    {{ user.avatar_url ? 'Change Photo' : 'Upload Photo' }}
                                </button>
                                <button
                                    v-if="user.avatar_url"
                                    @click="removeAvatar"
                                    class="inline-flex items-center px-4 py-2 text-red-600 hover:text-red-700 transition-colors"
                                >
                                    <TrashIcon class="w-4 h-4 mr-1" />
                                    Remove
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">JPG, PNG or GIF. Max 2MB.</p>
                            <p v-if="avatarForm.errors.avatar" class="mt-1 text-sm text-red-600">{{ avatarForm.errors.avatar }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <UserCircleIcon class="w-5 h-5 text-slate-400" />
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Profile Information</h2>
                            <p class="text-sm text-slate-500">Update your account's profile information and email address.</p>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="updateProfile" class="p-6 space-y-6">
                    <!-- Role Badge -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-brand-100 text-brand-700">
                            {{ roleLabel }}
                        </span>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                            Full Name
                        </label>
                        <input
                            id="name"
                            v-model="profileForm.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            :class="{ 'border-red-500': profileForm.errors.name }"
                        />
                        <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                            Email Address
                        </label>
                        <input
                            id="email"
                            v-model="profileForm.email"
                            type="email"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            :class="{ 'border-red-500': profileForm.errors.email }"
                        />
                        <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">{{ profileForm.errors.email }}</p>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="profileForm.processing"
                            class="px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Linked students (parents only; also when super admin previews as parent) -->
            <div v-if="showLinkedStudents" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <UserGroupIcon class="w-5 h-5 text-slate-400" />
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Linked Students</h2>
                            <p class="text-sm text-slate-500">Students linked to your account. Add another child with a Parent Code from the school.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div v-if="children?.length" class="space-y-2">
                        <p class="text-sm font-medium text-slate-700">Your linked students</p>
                        <ul class="list-disc list-inside text-slate-600 space-y-1">
                            <li v-for="child in children" :key="child.id">
                                {{ child.name }}
                                <span v-if="child.grade?.name" class="text-slate-500">({{ child.grade.name }})</span>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-sm text-slate-500">
                        No students linked yet. Use a Parent Code below to link a student.
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-sm font-medium text-slate-700 mb-2">Link another student</p>
                        <p v-if="addChildSuccess" class="text-sm text-green-700 mb-2">{{ addChildSuccess }}</p>
                        <p v-if="addChildError" class="text-sm text-red-600 mb-2">{{ addChildError }}</p>
                        <div class="flex gap-3 flex-wrap items-end">
                            <input
                                v-model="addChildCode"
                                type="text"
                                placeholder="Enter Parent Code"
                                class="flex-1 min-w-[180px] px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 font-mono uppercase"
                            />
                            <button
                                type="button"
                                :disabled="addChildSubmitting"
                                @click="addChild"
                                class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50"
                            >
                                <PlusIcon class="w-4 h-4 mr-2" />
                                {{ addChildSubmitting ? 'Adding...' : 'Add child' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <KeyIcon class="w-5 h-5 text-slate-400" />
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Update Password</h2>
                            <p class="text-sm text-slate-500">Ensure your account is using a secure password.</p>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="updatePassword" class="p-6 space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">
                            Current Password
                        </label>
                        <input
                            id="current_password"
                            v-model="passwordForm.current_password"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            :class="{ 'border-red-500': passwordForm.errors.current_password }"
                            placeholder="••••••••"
                        />
                        <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.current_password }}</p>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                            New Password
                        </label>
                        <input
                            id="password"
                            v-model="passwordForm.password"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            :class="{ 'border-red-500': passwordForm.errors.password }"
                            placeholder="••••••••"
                        />
                        <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password }}</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                            Confirm New Password
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="••••••••"
                        />
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="passwordForm.processing"
                            class="px-6 py-2 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Info -->
            <div class="bg-slate-50 rounded-xl p-6 text-center text-sm text-slate-500">
                <p>Account created on {{ new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
            </div>
        </div>
    </PortalLayout>
</template>
