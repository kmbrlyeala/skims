<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { ref } from 'vue';

const form = useForm({
    name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        name: `${data.name} ${data.last_name}`.trim(),
    })).post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Register">
        <div class="register-content">
            <!-- Heading -->
            <div class="register-header">
                <h2 class="register-header__title">Create Account ✨</h2>
                <p class="register-header__subtitle">Join SkimShop and start your glow journey</p>
            </div>

            <!-- Register Form -->
            <form @submit.prevent="submit" class="register-form">
                <!-- First Name & Last Name (side by side) -->
                <div class="register-row">
                    <div class="auth-field">
                        <label for="register-firstname" class="auth-field__label">First Name</label>
                        <div class="auth-field__wrapper">
                            <span class="auth-field__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input
                                id="register-firstname"
                                v-model="form.name"
                                type="text"
                                placeholder="First name"
                                required
                                autofocus
                                autocomplete="given-name"
                                class="auth-field__input"
                            />
                        </div>
                        <p v-if="form.errors.name" class="auth-field__error">{{ form.errors.name }}</p>
                    </div>

                    <div class="auth-field">
                        <label for="register-lastname" class="auth-field__label">Last Name</label>
                        <div class="auth-field__wrapper">
                            <span class="auth-field__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input
                                id="register-lastname"
                                v-model="form.last_name"
                                type="text"
                                placeholder="Last name"
                                required
                                autocomplete="family-name"
                                class="auth-field__input"
                            />
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="auth-field">
                    <label for="register-email" class="auth-field__label">Email Address</label>
                    <div class="auth-field__wrapper">
                        <span class="auth-field__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                        </span>
                        <input
                            id="register-email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter your email"
                            required
                            autocomplete="username"
                            class="auth-field__input"
                        />
                    </div>
                    <p v-if="form.errors.email" class="auth-field__error">{{ form.errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="auth-field">
                    <label for="register-password" class="auth-field__label">Password</label>
                    <div class="auth-field__wrapper">
                        <span class="auth-field__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input
                            id="register-password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Create a password"
                            required
                            autocomplete="new-password"
                            class="auth-field__input"
                        />
                        <button
                            type="button"
                            class="auth-field__toggle"
                            @click="showPassword = !showPassword"
                            tabindex="-1"
                        >
                            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
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

                <!-- Confirm Password -->
                <div class="auth-field">
                    <label for="register-password-confirm" class="auth-field__label">Confirm Password</label>
                    <div class="auth-field__wrapper">
                        <span class="auth-field__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input
                            id="register-password-confirm"
                            v-model="form.password_confirmation"
                            :type="showConfirmPassword ? 'text' : 'password'"
                            placeholder="Confirm your password"
                            required
                            autocomplete="new-password"
                            class="auth-field__input"
                        />
                        <button
                            type="button"
                            class="auth-field__toggle"
                            @click="showConfirmPassword = !showConfirmPassword"
                            tabindex="-1"
                        >
                            <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password_confirmation" class="auth-field__error">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Terms & Conditions -->
                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="register-terms">
                    <label class="register-terms__label">
                        <input
                            v-model="form.terms"
                            type="checkbox"
                            required
                            class="register-terms__checkbox"
                        />
                        <span class="register-terms__text">
                            I agree to the
                            <a :href="route('terms.show')" target="_blank" class="register-terms__link">Terms & Conditions</a>
                            and
                            <a :href="route('policy.show')" target="_blank" class="register-terms__link">Privacy Policy</a>
                        </span>
                    </label>
                    <p v-if="form.errors.terms" class="auth-field__error">{{ form.errors.terms }}</p>
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
                        Creating account…
                    </span>
                    <span v-else class="auth-submit__content">
                        Create Account
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
                Already have an account?
                <Link :href="route('login')" class="auth-footer__link">Login here</Link>
            </p>
        </template>
    </AuthLayout>
</template>

<style scoped>
.register-content {
    display: flex;
    flex-direction: column;
    gap: 1.125rem;
}

/* Header */
.register-header {
    text-align: center;
}

.register-header__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.register-header__subtitle {
    margin: 0.35rem 0 0;
    font-size: 0.875rem;
    color: #6b7280;
}

/* Form */
.register-form {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}

/* Form Row (side by side) */
.register-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
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
    padding: 0.7rem 0.875rem 0.7rem 2.75rem;
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

/* Terms */
.register-terms {
    margin-top: 0.125rem;
}

.register-terms__label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
}

.register-terms__checkbox {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    border: 1.5px solid #d1d5db;
    accent-color: #ec407a;
    cursor: pointer;
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.register-terms__text {
    font-size: 0.8125rem;
    color: #6b7280;
    line-height: 1.4;
}

.register-terms__link {
    color: #ec407a;
    font-weight: 500;
    text-decoration: none;
    transition: opacity 0.2s;
}

.register-terms__link:hover {
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

/* Responsive */
@media (max-width: 480px) {
    .register-row {
        grid-template-columns: 1fr;
    }
}
</style>
