<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref, computed } from 'vue';
import axios from 'axios';

const email = ref('');
const code = ref('');
const studentHint = ref(null);
const validating = ref(false);
const submitting = ref(false);
const success = ref(false);
const error = ref('');

const hintText = computed(() => {
  if (!studentHint.value) return null;
  const h = studentHint.value;
  const grade = h.grade?.name ?? '';
  const namePart = h.last_initial ? `${h.first_name} ${h.last_initial}.` : h.first_name;
  return grade ? `Code for ${namePart} (${grade})` : `Code for ${namePart}`;
});

const validateCode = async () => {
  const c = (code.value || '').trim();
  if (!c) {
    studentHint.value = null;
    return;
  }
  validating.value = true;
  studentHint.value = null;
  error.value = '';
  try {
    const { data } = await axios.post('/api/parent-code/validate', { code: c });
    if (data.valid && data.student_hint) {
      studentHint.value = data.student_hint;
    }
  } catch {
    studentHint.value = null;
  } finally {
    validating.value = false;
  }
};

const submit = async () => {
  error.value = '';
  if (!email.value.trim() || !code.value.trim()) {
    error.value = 'Please enter your email and parent code.';
    return;
  }
  submitting.value = true;
  try {
    const { data } = await axios.post('/api/parent-code/signup', {
      email: email.value.trim().toLowerCase(),
      code: code.value.trim(),
    });
    if (data.ok) {
      success.value = true;
    } else {
      error.value = data.message || 'Something went wrong. Please try again.';
    }
  } catch (err) {
    const msg = err.response?.data?.message || 'Invalid code or this student has reached the maximum number of linked accounts. Contact the school office.';
    error.value = msg;
  } finally {
    submitting.value = false;
  }
};
</script>

<template>
  <Head title="Parent signup" />

  <GuestLayout>
    <div v-if="success" class="text-center space-y-4">
      <h1 class="text-2xl font-bold text-slate-900">Check your email</h1>
      <p class="text-slate-600">
        We've sent your password and a link to log in. Use them to sign in to the Family Portal.
      </p>
      <Link
        href="/login"
        class="inline-block rounded-lg bg-brand-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-brand-500"
      >
        Go to login
      </Link>
    </div>

    <template v-else>
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Create your account</h1>
        <p class="text-slate-600 mt-2">Sign up with your email and Parent Code</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <p v-if="error" class="text-sm text-red-600 bg-red-50 p-3 rounded-lg">
          {{ error }}
        </p>

        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
            Email address
          </label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            autocomplete="email"
            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label for="code" class="block text-sm font-medium text-slate-700 mb-2">
            Parent Code
          </label>
          <input
            id="code"
            v-model="code"
            type="text"
            required
            autocomplete="off"
            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 px-4 py-3 border font-mono uppercase"
            placeholder="Enter the code from the school"
            @blur="validateCode"
          />
          <p v-if="validating" class="mt-2 text-sm text-slate-500">Checking code...</p>
          <p v-else-if="hintText" class="mt-2 text-sm text-green-700">
            {{ hintText }}
          </p>
        </div>

        <button
          type="submit"
          :disabled="submitting"
          class="w-full rounded-lg bg-brand-600 px-6 py-4 text-base font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
        >
          <span v-if="!submitting">Create my account</span>
          <span v-else>Creating account...</span>
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-slate-600">
          Already have an account?
          <Link href="/login" class="font-medium text-brand-600 hover:text-brand-500">
            Log in
          </Link>
        </p>
      </div>
    </template>
  </GuestLayout>
</template>
