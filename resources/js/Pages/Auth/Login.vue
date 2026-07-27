<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { ref } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

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
        <div class="login-content">
            <!-- Heading -->
            <div class="login-header">
                <h2 class="login-header__title">Welcome Back! ✨</h2>
                <p class="login-header__subtitle">Login to continue to your account</p>
            </div>

            <!-- Status Message -->
            <div v-if="status" class="login-status">
                {{ status }}
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="login-form">
                <!-- Email -->
                <div class="auth-field">
                    <label for="login-email" class="auth-field__label">Email Address</label>
                    <div class="auth-field__wrapper">
                        <span class="auth-field__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                        </span>
                        <input
                            id="login-email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter your email"
                            required
                            autofocus
                            autocomplete="username"
                            class="auth-field__input"
                        />
                    </div>
                    <p v-if="form.errors.email" class="auth-field__error">{{ form.errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="auth-field">
                    <label for="login-password" class="auth-field__label">Password</label>
                    <div class="auth-field__wrapper">
                        <span class="auth-field__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input
                            id="login-password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                            class="auth-field__input"
                        />
                        <button
                            type="button"
                            class="auth-field__toggle"
                            @click="showPassword = !showPassword"
                            tabindex="-1"
                        >
                            <!-- Eye icon -->
                            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <!-- Eye-off icon -->
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password" class="auth-field__error">{{ form.errors.password }}</p>
                </div>

                <!-- Remember & Forgot -->
                <div class="login-options">
                    <label class="login-remember">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="login-remember__checkbox"
                        />
                        <span class="login-remember__text">Remember me</span>
                    </label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="login-forgot"
                    >
                        Forgot Password?
                    </Link>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="auth-submit"
                >
                    <span v-if="form.processing" class="auth-submit__loading">
                        <svg class="auth-spinner" viewBox="0 0 24 24" fill="none">
                            <circle class="auth-spinner__track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="auth-spinner__head" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Signing in…
                    </span>
                    <span v-else class="auth-submit__content">
                        Login
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="auth-divider">
                <span class="auth-divider__text">or continue with</span>
            </div>

            <!-- Social Buttons -->
            <div class="auth-social">
                <button type="button" class="auth-social__btn">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </button>
                <button type="button" class="auth-social__btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </button>
            </div>
        </div>

        <template #footer>
            <p class="auth-footer__text">
                Don't have an account?
                <Link :href="route('register')" class="auth-footer__link">Register now</Link>
            </p>
        </template>
    </AuthLayout>
</template>

<style scoped>
.login-content {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Header */
.login-header {
    text-align: center;
}

.login-header__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.login-header__subtitle {
    margin: 0.35rem 0 0;
    font-size: 0.875rem;
    color: #6b7280;
}

/* Status */
.login-status {
    padding: 0.75rem 1rem;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 0.75rem;
    color: #065f46;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Form */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Auth Field */
.auth-field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.auth-field__label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #374151;
}

.auth-field__wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.auth-field__icon {
    position: absolute;
    left: 0.875rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    pointer-events: none;
    z-index: 1;
}

.auth-field__input {
    width: 100%;
    padding: 0.75rem 0.875rem 0.75rem 2.75rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 0.75rem;
    font-size: 0.875rem;
    color: #1f2937;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.auth-field__input::placeholder {
    color: #9ca3af;
}

.auth-field__input:focus {
    border-color: #f48fb1;
    box-shadow: 0 0 0 3px rgba(244, 143, 177, 0.15);
    background: rgba(255, 255, 255, 0.9);
}

.auth-field__toggle {
    position: absolute;
    right: 0.75rem;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 0.25rem;
    border-radius: 0.375rem;
    transition: color 0.2s;
}

.auth-field__toggle:hover {
    color: #6b7280;
}

.auth-field__error {
    margin: 0;
    font-size: 0.8125rem;
    color: #dc2626;
}

/* Remember & Forgot */
.login-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.login-remember {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.login-remember__checkbox {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    border: 1.5px solid #d1d5db;
    accent-color: #ec407a;
    cursor: pointer;
}

.login-remember__text {
    font-size: 0.8125rem;
    color: #6b7280;
}

.login-forgot {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #ec407a;
    text-decoration: none;
    transition: opacity 0.2s;
}

.login-forgot:hover {
    opacity: 0.75;
}

/* Submit Button */
.auth-submit {
    width: 100%;
    padding: 0.8rem 1.5rem;
    background: linear-gradient(135deg, #f48fb1 0%, #ec407a 50%, #e91e63 100%);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
    position: relative;
    overflow: hidden;
}

.auth-submit::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    opacity: 0;
    transition: opacity 0.25s;
}

.auth-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
}

.auth-submit:hover:not(:disabled)::before {
    opacity: 1;
}

.auth-submit:active:not(:disabled) {
    transform: translateY(0);
}

.auth-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.auth-submit__content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.auth-submit__loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.auth-spinner {
    width: 1.25rem;
    height: 1.25rem;
    animation: auth-spin 1s linear infinite;
}

.auth-spinner__track {
    opacity: 0.25;
}

.auth-spinner__head {
    opacity: 0.75;
}

@keyframes auth-spin {
    to { transform: rotate(360deg); }
}

/* Divider */
.auth-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(0, 0, 0, 0.08);
}

.auth-divider__text {
    font-size: 0.8125rem;
    color: #9ca3af;
    white-space: nowrap;
}

/* Social Buttons */
.auth-social {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.auth-social__btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
}

.auth-social__btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

/* Footer */
.auth-footer__text {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.auth-footer__link {
    font-weight: 600;
    color: #ec407a;
    text-decoration: none;
    margin-left: 0.25rem;
    transition: opacity 0.2s;
}

.auth-footer__link:hover {
    opacity: 0.75;
}
</style>
