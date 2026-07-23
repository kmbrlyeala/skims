<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout title="Login">
        <div class="space-y-5">
            <div class="text-center lg:text-left">
                <div class="mb-3 inline-flex items-center rounded-full border border-pink-200 bg-pink-50/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-pink-600">
                    Welcome back
                </div>
                <h2 class="text-2xl font-semibold text-slate-800">Sign in to your glow routine</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Access your favorites, track your orders, and continue your beauty essentials ritual.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-2 lg:justify-start">
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">Cruelty-free</span>
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">Vegan</span>
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">Fast delivery</span>
            </div>

            <div
                v-if="status"
                class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm font-medium text-emerald-700"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="login-email" class="mb-1.5 block font-semibold text-slate-700">
                        Email
                    </label>
                    <input
                        id="login-email"
                        v-model="form.email"
                        type="email"
                        placeholder="you@example.com"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-3 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="login-password" class="mb-1.5 block font-semibold text-slate-700">
                        Password
                    </label>
                    <input
                        id="login-password"
                        v-model="form.password"
                        type="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-3 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-600">{{ form.errors.password }}</p>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-pink-500 focus:ring-pink-200"
                        />
                        <span class="text-sm text-slate-500">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-pink-600 no-underline transition-opacity hover:opacity-80"
                    >
                        Forgot password?
                    </Link>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-2xl border-0 bg-gradient-to-r from-pink-500 via-rose-500 to-violet-500 px-3.5 py-3 font-semibold text-white shadow-lg shadow-pink-200 transition-all duration-200 hover:translate-y-[-1px] hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Signing in…
                    </span>
                    <span v-else>Sign In</span>
                </button>
            </form>
        </div>

        <template #footer>
            <p class="text-sm text-slate-600">
                Don't have an account?
                <Link :href="route('register')" class="font-semibold text-pink-600 no-underline transition-opacity hover:opacity-80">
                    Register
                </Link>
            </p>
        </template>
    </AuthLayout>
</template>
