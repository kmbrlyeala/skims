<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Register">
        <div class="space-y-4">
            <div class="text-center lg:text-left">
                <div class="mb-2 inline-flex items-center rounded-full border border-pink-200 bg-pink-50/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-pink-600">
                    Join the club
                </div>
                <h2 class="text-xl font-semibold text-slate-800">Create your beauty profile</h2>
                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Start with your essentials and enjoy a smoother beauty shopping experience.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-2 lg:justify-start">
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">New arrivals</span>
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">Gift-ready</span>
                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">Free samples</span>
            </div>

            <form @submit.prevent="submit" class="space-y-3">
                <div>
                    <label for="register-name" class="mb-1.5 block font-semibold text-slate-700">
                        Full Name
                    </label>
                    <input
                        id="register-name"
                        v-model="form.name"
                        type="text"
                        placeholder="Alex Morgan"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-2.5 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.name" class="mt-1.5 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="register-email" class="mb-1.5 block font-semibold text-slate-700">
                        Email
                    </label>
                    <input
                        id="register-email"
                        v-model="form.email"
                        type="email"
                        placeholder="you@example.com"
                        required
                        autocomplete="username"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-2.5 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="register-password" class="mb-1.5 block font-semibold text-slate-700">
                        Password
                    </label>
                    <input
                        id="register-password"
                        v-model="form.password"
                        type="password"
                        placeholder="Create a password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-2.5 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-600">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label for="register-password-confirm" class="mb-1.5 block font-semibold text-slate-700">
                        Confirm Password
                    </label>
                    <input
                        id="register-password-confirm"
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="Re-enter password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-2xl border border-white/70 bg-white/70 px-3.5 py-2.5 text-base text-slate-700 shadow-sm outline-none transition focus:border-pink-400 focus:ring-2 focus:ring-pink-200"
                    />
                    <p v-if="form.errors.password_confirmation" class="mt-1.5 text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
                </div>

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-2">
                    <label class="flex cursor-pointer items-start gap-2">
                        <input
                            v-model="form.terms"
                            type="checkbox"
                            required
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-pink-500 focus:ring-pink-200"
                        />
                        <span class="text-sm leading-snug text-slate-500">
                            I agree to the
                            <a :href="route('terms.show')" target="_blank" class="font-medium text-pink-600 underline">Terms of Service</a>
                            and
                            <a :href="route('policy.show')" target="_blank" class="font-medium text-pink-600 underline">Privacy Policy</a>
                        </span>
                    </label>
                    <p v-if="form.errors.terms" class="mt-1.5 text-sm text-red-600">{{ form.errors.terms }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-2xl border-0 bg-gradient-to-r from-pink-500 via-rose-500 to-violet-500 px-3.5 py-2.5 font-semibold text-white shadow-lg shadow-pink-200 transition-all duration-200 hover:translate-y-[-1px] hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Creating account…
                    </span>
                    <span v-else>Create Account</span>
                </button>
            </form>
        </div>

        <template #footer>
            <p class="text-sm text-slate-600">
                Already have an account?
                <Link :href="route('login')" class="font-semibold text-pink-600 no-underline transition-opacity hover:opacity-80">
                    Sign In
                </Link>
            </p>
        </template>
    </AuthLayout>
</template>
