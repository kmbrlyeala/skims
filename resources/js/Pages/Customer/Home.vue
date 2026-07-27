<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Home">
        <div class="space-y-12 pb-16">
            
            <!-- Hero / Search Section -->
            <div class="relative bg-gradient-to-r from-pink-100 to-violet-100 px-6 py-16 sm:px-12 sm:py-24 rounded-3xl overflow-hidden shadow-inner">
                <div class="relative z-10 max-w-2xl mx-auto text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                        Discover Your Glow
                    </h1>
                    <p class="mt-4 text-lg text-slate-600">
                        Shop the latest and greatest in skincare and beauty.
                    </p>
                    <div class="mt-8 flex justify-center">
                        <div class="relative w-full max-w-lg">
                            <input type="text" class="w-full rounded-full border-0 py-4 pl-6 pr-14 shadow-md ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-pink-500 sm:text-sm sm:leading-6" placeholder="Search products...">
                            <button class="absolute right-2 top-2 rounded-full bg-pink-500 p-2 text-white shadow hover:bg-pink-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-violet-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            </div>

            <!-- Categories -->
            <div class="px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Shop by Category</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-6">
                    <div class="group cursor-pointer flex flex-col items-center gap-3">
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-pink-50 transition-colors group-hover:bg-pink-100 shadow-sm">
                            <span class="text-3xl">✨</span>
                        </div>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-pink-600 transition-colors">Skincare</span>
                    </div>
                    <div class="group cursor-pointer flex flex-col items-center gap-3">
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-violet-50 transition-colors group-hover:bg-violet-100 shadow-sm">
                            <span class="text-3xl">💄</span>
                        </div>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-violet-600 transition-colors">Makeup</span>
                    </div>
                    <div class="group cursor-pointer flex flex-col items-center gap-3">
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-emerald-50 transition-colors group-hover:bg-emerald-100 shadow-sm">
                            <span class="text-3xl">🧴</span>
                        </div>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-600 transition-colors">Body Care</span>
                    </div>
                    <div class="group cursor-pointer flex flex-col items-center gap-3">
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-50 transition-colors group-hover:bg-blue-100 shadow-sm">
                            <span class="text-3xl">💆‍♀️</span>
                        </div>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-blue-600 transition-colors">Hair Care</span>
                    </div>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Featured Products</h2>
                    <a href="#" class="text-sm font-medium text-pink-600 hover:text-pink-500">View all &rarr;</a>
                </div>
                <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <!-- Product Card -->
                    <div class="group relative">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center text-4xl shadow-sm transition-transform group-hover:scale-105">
                            🧴
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-slate-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Hydrating Moisturizer
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">Skincare</p>
                            </div>
                            <p class="text-sm font-bold text-slate-900">₱450.00</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center text-4xl shadow-sm transition-transform group-hover:scale-105">
                            ✨
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-slate-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Vitamin C Serum
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">Skincare</p>
                            </div>
                            <p class="text-sm font-bold text-slate-900">₱850.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Arrivals -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">New Arrivals</h2>
                    <a href="#" class="text-sm font-medium text-pink-600 hover:text-pink-500">View all &rarr;</a>
                </div>
                <div class="glass-card flex items-center justify-center p-12">
                    <p class="text-slate-400 italic">New products will appear here...</p>
                </div>
            </div>

            <!-- Best Sellers -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Best Sellers</h2>
                    <a href="#" class="text-sm font-medium text-pink-600 hover:text-pink-500">View all &rarr;</a>
                </div>
                <div class="glass-card flex items-center justify-center p-12">
                    <p class="text-slate-400 italic">Best selling products will appear here...</p>
                </div>
            </div>

            <!-- Sale Products -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-red-600 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" /></svg>
                        Sale Products
                    </h2>
                    <a href="#" class="text-sm font-medium text-red-600 hover:text-red-500">View all &rarr;</a>
                </div>
                <div class="glass-card flex items-center justify-center p-12">
                    <p class="text-slate-400 italic">Discounted products will appear here...</p>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
