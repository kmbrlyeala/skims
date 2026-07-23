<script setup>
import { Head, Link } from '@inertiajs/vue3';
const backgroundImage = new URL('./skim.png', import.meta.url).href;
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});


</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen overflow-hidden bg-gradient-to-br from-slate-75 via-white to-pink-60/40"
    :style="{ backgroundImage: `linear-gradient(rgba(255, 255, 255, 0.40), rgba(255, 255, 255, 0.15)), url('${backgroundImage}')`, backgroundSize: 'cover', backgroundPosition: 'center', backgroundRepeat: 'no-repeat' }"
    >
        <!-- Nav -->
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-violet-500 text-sm font-bold text-white shadow-lg shadow-pink-200">
                    S
                </div>
                <span class="text-base font-bold tracking-wide text-slate-800">SKIMS SHOP</span>
            </div>
            <div class="flex items-center gap-3">
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 bg-white shadow transition-colors hover:bg-slate-100"
                >
                    Sign In
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105"
                >
                    Get Started
                </Link>
            </div>
        </nav>
        <br><br>
        <!-- Hero -->
        <section class="mx-auto max-w-7xl px-6 pb-16 pt-12 text-center sm:pt-20 w-full max-w-5xl rounded-[2.5rem] bg-white/50 backdrop-blur-xl border border-white/60 shadow-[0_25px_80px_rgba(15,23,42,0.08)]">
            <div class="mb-6 inline-flex items-center rounded-full border border-pink-200 bg-pink-50/80 px-4 py-1.5 text-lg font-semibold uppercase tracking-[0.25em] text-pink-600">
                ✦ Beauty essentials, elevated
            </div>
            <h1 class="mx-auto max-w-4xl text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl lg:text-7xl">
                Your glow routine,
                <span class="bg-gradient-to-r from-pink-500 via-rose-500 to-violet-500 bg-clip-text text-transparent">
                    reimagined.
                </span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-500">
                Discover luxe skincare, haircare, and self-care essentials from independent suppliers.
                Curated for confidence, delivered with care.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-pink-500 via-rose-500 to-violet-500 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-pink-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl"
                >
                    Start Shopping
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-7 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50"
                >
                    Sign In
                </Link>
            </div>
        </section>

        <!-- Features -->
        <section class="mx-auto max-w-7xl px-6 pb-20">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
                >
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-pink-50 to-violet-50 text-lg">
                        {{ feature.icon }}
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">{{ feature.title }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ feature.desc }}</p>
                </div>
            </div>
        </section>

       <br><br>
        <!-- Footer -->
        <footer class=" py-8 text-center">
            <p class="text-xs text-slate-400">&copy; 2026 SKIMS SHOP. All rights reserved.</p>
        </footer>
    </div>
</template>
