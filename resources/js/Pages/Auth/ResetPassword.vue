<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Reset Password">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Reset Password</h2>

        <form @submit.prevent="submit" class="space-y-3">
            <!-- Email (read-only, pre-filled from token link) -->
            <div>
                <label for="reset-email" class="block font-semibold text-gray-900 mb-1.5">
                    Email
                </label>
                <input
                    id="reset-email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    readonly
                    class="w-full px-3.5 py-3 border border-border rounded-[10px] text-base bg-gray-50 text-muted cursor-not-allowed"
                />
                <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- New Password -->
            <div>
                <label for="reset-password" class="block font-semibold text-gray-900 mb-1.5">
                    New Password
                </label>
                <input
                    id="reset-password"
                    v-model="form.password"
                    type="password"
                    placeholder="Create a new password"
                    required
                    autofocus
                    autocomplete="new-password"
                    class="w-full px-3.5 py-3 border border-border rounded-[10px] text-base bg-white focus:outline-2 focus:outline-accent/20 focus:border-accent transition-colors"
                />
                <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-600">{{ form.errors.password }}</p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="reset-password-confirm" class="block font-semibold text-gray-900 mb-1.5">
                    Confirm Password
                </label>
                <input
                    id="reset-password-confirm"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Re-enter new password"
                    required
                    autocomplete="new-password"
                    class="w-full px-3.5 py-3 border border-border rounded-[10px] text-base bg-white focus:outline-2 focus:outline-accent/20 focus:border-accent transition-colors"
                />
                <p v-if="form.errors.password_confirmation" class="mt-1.5 text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
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
                    Resetting…
                </span>
                <span v-else>Reset Password</span>
            </button>
        </form>
    </AuthLayout>
</template>
