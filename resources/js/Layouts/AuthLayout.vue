<script setup>
import { Head } from '@inertiajs/vue3';

const backgroundImage = new URL('./skim.png', import.meta.url).href;

defineProps({
    title: String,
});
</script>

<template>
    <!-- Full page gradient -->
    <div
        class="min-h-screen overflow-hidden flex items-center justify-center p-4 sm:p-6 lg:p-8"
        :style="{ backgroundImage: `linear-gradient(rgba(255, 255, 255, 0.45), rgba(248, 250, 252, 0.72)), url('${backgroundImage}')`, backgroundSize: 'cover', backgroundPosition: 'center', backgroundRepeat: 'no-repeat' }"
    >
        <Head :title="title" />

        <!-- Logo top left -->
        <div class="absolute left-6 top-6 lg:left-10 lg:top-10">
            <a href="/" class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-violet-500 text-sm font-bold text-white shadow-lg shadow-pink-200">
                    S
                </div>
                <span class="text-sm font-bold tracking-wide text-slate-800">SKIMS SHOP</span>
            </a>
        </div>

        <!-- Main Glass Container -->
        <div class="w-full max-w-5xl rounded-[2.5rem] bg-white/30 backdrop-blur-xl border border-white/70 shadow-[0_25px_80px_rgba(15,23,42,0.12)] overflow-hidden flex flex-col lg:flex-row">
            
            <!-- Left Side: Blurb -->
            <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-center p-10 xl:p-14 border-r border-white/40">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.35em] text-pink-500">
                    Beauty essentials
                </p>
                <h1 class="text-4xl font-semibold tracking-tight text-slate-800 xl:text-5xl">
                    Curated glow for your everyday ritual.
                </h1>
                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Discover luxe skincare, haircare, and self-care essentials designed to feel as polished as they look.
                </p>

                <div class="mt-10 flex flex-wrap gap-3">
                    <span class="rounded-full border border-pink-200 bg-pink-50/80 px-4 py-2 text-sm font-medium text-pink-700 shadow-sm">
                        Cruelty-free
                    </span>
                    <span class="rounded-full border border-violet-200 bg-violet-50/80 px-4 py-2 text-sm font-medium text-violet-700 shadow-sm">
                        Dermatologist tested
                    </span>
                    <span class="rounded-full border border-amber-200 bg-amber-50/80 px-4 py-2 text-sm font-medium text-amber-700 shadow-sm">
                        Fast dispatch
                    </span>
                </div>

                <div class="mt-12 rounded-[24px] border border-white/70 bg-white/50 p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-violet-500 text-xl text-white shadow-lg shadow-pink-200">
                            ✦
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Glow edit</p>
                            <p class="mt-0.5 text-sm text-slate-600">Hydration, softness, and shine in one place.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Auth Form -->
            <div class="w-full p-8 sm:p-12 lg:w-1/2 lg:p-14 xl:p-16 flex flex-col justify-center bg-white/65">
                
                <!-- Mobile logo -->
                <div class="mb-8 flex justify-center lg:hidden">
                    <a href="/" class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-violet-500 text-sm font-bold text-white shadow-lg shadow-pink-200">
                            S
                        </div>
                        <span class="text-sm font-bold tracking-wide text-slate-800">SKIMS SHOP</span>
                    </a>
                </div>

                <div class="mx-auto w-full max-w-[420px]">
                    <!-- Render the Auth slot directly (no extra white box) -->
                    <slot />

                    <div v-if="$slots.footer" class="mt-8 text-center pt-6 border-t border-slate-200/60">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
