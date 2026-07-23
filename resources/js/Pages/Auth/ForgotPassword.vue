<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout title="Forgot Password">
        <h2 class="text-xl font-bold text-gray-900 mb-3">Forgot Password</h2>

        <p class="text-sm text-muted leading-relaxed mb-4">
            No problem. Enter your email address and we'll send you a link to reset your password.
        </p>

        <!-- Success status -->
        <div
            v-if="status"
            class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-sm font-medium text-green-700"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-3">
            <!-- Email -->
            <div>
                <label for="forgot-email" class="block font-semibold text-gray-900 mb-1.5">
                    Email
                </label>
                <input
                    id="forgot-email"
                    v-model="form.email"
                    type="email"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-3.5 py-3 border border-border rounded-[10px] text-base bg-white focus:outline-2 focus:outline-accent/20 focus:border-accent transition-colors"
                />
                <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full border-0 rounded-[10px] px-3.5 py-3 bg-accent text-white font-bold cursor-pointer hover:opacity-95 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    Sending…
                </span>
                <span v-else>Email Password Reset Link</span>
            </button>
        </form>

        <template #footer>
            <Link :href="route('login')" class="text-accent font-semibold text-sm no-underline hover:opacity-80 transition-opacity">
                ← Back to Login
            </Link>
        </template>
    </AuthLayout>
</template>
