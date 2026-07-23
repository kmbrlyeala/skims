<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <AuthLayout title="Email Verification">
        <h2 class="text-xl font-bold text-gray-900 mb-3">Verify Your Email</h2>

        <p class="text-sm text-muted leading-relaxed mb-4">
            Before continuing, please verify your email address by clicking on the link we just emailed to you.
            If you didn't receive the email, we'll gladly send you another.
        </p>

        <!-- Success status -->
        <div
            v-if="verificationLinkSent"
            class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-sm font-medium text-green-700"
        >
            A new verification link has been sent to your email address.
        </div>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-between gap-3">
                <!-- Resend button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex-1 border-0 rounded-[10px] px-3.5 py-3 bg-accent text-white font-bold cursor-pointer hover:opacity-95 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Sending…
                    </span>
                    <span v-else>Resend Verification Email</span>
                </button>

                <!-- Logout -->
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="px-4 py-3 rounded-[10px] bg-surface-alt border border-border text-accent font-semibold text-sm cursor-pointer hover:opacity-80 transition-opacity"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </AuthLayout>
</template>
