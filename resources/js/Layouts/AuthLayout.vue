<script setup>
import { Head } from '@inertiajs/vue3';

const backgroundImage = new URL('./skim.png', import.meta.url).href;

defineProps({
    title: String,
});
</script>

<template>
    <div class="auth-page" :style="{ backgroundImage: `linear-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4)), url('${backgroundImage}')`, backgroundSize: 'cover', backgroundPosition: 'center', backgroundRepeat: 'no-repeat' }">
        <Head :title="title" />

        <!-- Decorative background blobs -->
        <div class="auth-bg-blob auth-bg-blob--1"></div>
        <div class="auth-bg-blob auth-bg-blob--2"></div>
        <div class="auth-bg-blob auth-bg-blob--3"></div>

        <!-- Main Glass Card -->
        <div class="auth-card">
            <!-- Left Side (Design) -->
            <div class="auth-card__left">
                <div class="auth-card__left-inner">
                    <a href="/" class="auth-logo auth-logo--light">
                        <div class="auth-logo__icon bg-white text-pink-500 shadow-md">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0L12 2.69z" />
                            </svg>
                        </div>
                        <span class="auth-logo__text text-white">
                            Skim<span class="text-pink-200">Shop</span>
                        </span>
                    </a>

                    <div class="mt-auto mb-10">
                        <h1 class="text-3xl font-bold text-white mb-4">Hey, Hello!</h1>
                        <p class="text-pink-100 text-sm leading-relaxed max-w-xs">
                            Discover luxe skincare, haircare, and self-care essentials from independent suppliers. Curated for confidence, delivered with care.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side (Form) -->
            <div class="auth-card__right">
                <!-- Mobile Logo -->
                <div class="sm:hidden flex justify-center mb-6">
                    <a href="/" class="auth-logo">
                        <div class="auth-logo__icon bg-gradient-to-br from-pink-500 to-rose-500 text-white shadow-md">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0L12 2.69z" />
                            </svg>
                        </div>
                        <span class="auth-logo__text text-slate-800">
                            Skim<span class="text-pink-500">Shop</span>
                        </span>
                    </a>
                </div>

                <div class="auth-card__body w-full max-w-sm mx-auto">
                    <slot />
                </div>

                <div v-if="$slots.footer" class="auth-card__footer w-full max-w-sm mx-auto">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    font-family: 'Inter', sans-serif;
}

/* Decorative floating blobs */
.auth-bg-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.6;
    pointer-events: none;
    z-index: 0;
}

.auth-bg-blob--1 {
    width: 400px;
    height: 400px;
    background: rgba(236, 64, 122, 0.5);
    top: -100px;
    left: -100px;
    animation: float 8s ease-in-out infinite;
}

.auth-bg-blob--2 {
    width: 350px;
    height: 350px;
    background: rgba(156, 39, 176, 0.4);
    bottom: -80px;
    right: -80px;
    animation: float 10s ease-in-out infinite reverse;
}

.auth-bg-blob--3 {
    width: 250px;
    height: 250px;
    background: rgba(244, 143, 177, 0.6);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: float 12s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-30px); }
}

.auth-card {
    width: 100%;
    max-width: 900px;
    min-height: 550px;
    /* Glassmorphism Effect */
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 1.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.6);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    z-index: 1;
    animation: cardAppear 0.5s ease-out;
}

@media (min-width: 768px) {
    .auth-card {
        flex-direction: row;
    }
}

.auth-card__left {
    display: none;
    position: relative;
    /* Semi-transparent pink gradient on the left side of the glass card */
    background: linear-gradient(135deg, rgba(236, 64, 122, 0.75), rgba(156, 39, 176, 0.75));
}

@media (min-width: 768px) {
    .auth-card__left {
        display: flex;
        flex: 1 1 45%;
        max-width: 45%;
    }
}

.auth-card__left-inner {
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.auth-card__right {
    flex: 1;
    padding: 2.5rem 2rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    /* Solid enough white to make the form readable */
    background: rgba(255, 255, 255, 0.65);
}

@media (min-width: 768px) {
    .auth-card__right {
        padding: 3rem;
    }
}

/* Logo Styles */
.auth-logo {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: opacity 0.2s;
}

.auth-logo:hover {
    opacity: 0.9;
}

.auth-logo__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
}

.auth-logo__text {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.auth-card__footer {
    margin-top: 1.5rem;
    text-align: center;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(0,0,0,0.05);
}

@keyframes cardAppear {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
