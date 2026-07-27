<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    featuredProducts: {
        type: Array,
        default: () => []
    },
    newArrivals: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <AppLayout title="Discover Your Glow">
        <div class="space-y-16 pb-20">
            
            <!-- Hero / Search Section -->
            <div class="relative px-6 py-20 sm:px-12 sm:py-32 rounded-3xl overflow-hidden shadow-2xl mx-4 sm:mx-8 mt-6 border border-white/40">
                <!-- Dynamic Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-pink-100 via-rose-50 to-violet-100 opacity-90 z-0"></div>
                <div class="absolute -top-32 -right-32 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob z-0"></div>
                <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-violet-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-2000 z-0"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-rose-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-50 animate-blob animation-delay-4000 z-0"></div>

                <div class="relative z-10 max-w-3xl mx-auto text-center">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/60 text-pink-600 text-xs font-bold tracking-widest uppercase mb-6 shadow-sm backdrop-blur-md border border-white">
                        Premium Beauty E-Commerce
                    </span>
                    <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl font-sans drop-shadow-sm">
                        Discover Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-violet-600">Glow</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-700 font-medium max-w-xl mx-auto leading-relaxed">
                        Shop the latest and greatest in curated skincare, makeup, and self-care essentials.
                    </p>
                    <div class="mt-10 flex justify-center">
                        <Link :href="route('customer.shop')" class="btn-primary px-8 py-4 text-lg shadow-pink-500/30">
                            Shop the Collection
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="px-6 sm:px-12">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Shop by Category</h2>
                        <p class="text-sm text-slate-500 mt-1">Explore our curated collections</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-4 xl:gap-8">
                    <!-- Skincare -->
                    <Link :href="route('customer.shop', { search: 'skincare' })" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:ring-pink-200">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-pink-50 transition-transform duration-500 group-hover:scale-150 group-hover:bg-pink-100"></div>
                        <div class="relative flex flex-col items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 text-2xl group-hover:ring-pink-200 transition-colors">✨</div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-pink-600 transition-colors">Skincare</h3>
                                <p class="text-xs text-slate-500 mt-1">Cleansers, serums & more</p>
                            </div>
                        </div>
                    </Link>
                    <!-- Makeup -->
                    <Link :href="route('customer.shop', { search: 'makeup' })" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:ring-violet-200">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-violet-50 transition-transform duration-500 group-hover:scale-150 group-hover:bg-violet-100"></div>
                        <div class="relative flex flex-col items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 text-2xl group-hover:ring-violet-200 transition-colors">💄</div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-violet-600 transition-colors">Makeup</h3>
                                <p class="text-xs text-slate-500 mt-1">Face, eyes & lips</p>
                            </div>
                        </div>
                    </Link>
                    <!-- Body Care -->
                    <Link :href="route('customer.shop', { search: 'body' })" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:ring-emerald-200">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-50 transition-transform duration-500 group-hover:scale-150 group-hover:bg-emerald-100"></div>
                        <div class="relative flex flex-col items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 text-2xl group-hover:ring-emerald-200 transition-colors">🧴</div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Body Care</h3>
                                <p class="text-xs text-slate-500 mt-1">Lotions & scrubs</p>
                            </div>
                        </div>
                    </Link>
                    <!-- Hair Care -->
                    <Link :href="route('customer.shop', { search: 'hair' })" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:ring-blue-200">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150 group-hover:bg-blue-100"></div>
                        <div class="relative flex flex-col items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 text-2xl group-hover:ring-blue-200 transition-colors">💆‍♀️</div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Hair Care</h3>
                                <p class="text-xs text-slate-500 mt-1">Shampoos & treatments</p>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="px-6 sm:px-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Featured Products</h2>
                    <Link :href="route('customer.shop')" class="text-sm font-bold text-pink-600 hover:text-pink-500 transition-colors">View all &rarr;</Link>
                </div>
                
                <div v-if="featuredProducts.length > 0" class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <div v-for="product in featuredProducts" :key="product.id" class="group relative flex flex-col bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden bg-slate-50 relative">
                            <img v-if="product.photo_url" :src="product.photo_url" :alt="product.name" class="h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-110" />
                            <div v-else class="flex h-full items-center justify-center text-4xl text-slate-300">✦</div>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <span v-if="product.stock < 1" class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Out of Stock</span>
                                <span v-else class="bg-white/80 backdrop-blur-md text-slate-900 text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">Bestseller</span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-base font-bold text-slate-900 truncate">
                                <Link :href="route('customer.shop', { search: product.name })">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ product.name }}
                                </Link>
                            </h3>
                            <div class="mt-auto pt-4 flex items-center justify-between relative z-10">
                                <p class="text-lg font-extrabold text-slate-900">₱{{ Number(product.price).toFixed(2) }}</p>
                                <button class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 transition-colors group-hover:bg-pink-500 group-hover:text-white" :disabled="product.stock < 1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="glass-card flex items-center justify-center p-12">
                    <p class="text-slate-400 italic">No featured products available.</p>
                </div>
            </div>

            <!-- New Arrivals Banner -->
            <div class="px-6 sm:px-12">
                <div class="relative bg-slate-900 rounded-3xl overflow-hidden shadow-2xl flex flex-col sm:flex-row items-center justify-between p-10 sm:p-16">
                    <div class="absolute inset-0 opacity-40 mix-blend-color-dodge">
                        <div class="absolute -top-24 -right-24 w-96 h-96 bg-violet-500 rounded-full mix-blend-screen filter blur-[100px]"></div>
                        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-pink-500 rounded-full mix-blend-screen filter blur-[100px]"></div>
                    </div>
                    <div class="relative z-10 text-center sm:text-left mb-8 sm:mb-0 max-w-lg">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl tracking-tight">The Summer Collection</h2>
                        <p class="mt-4 text-slate-300 text-lg">Refresh your routine with our newest arrivals, designed to keep you glowing all season long.</p>
                        <Link :href="route('customer.shop')" class="mt-8 inline-block btn-primary shadow-pink-500/50">
                            Explore New Arrivals
                        </Link>
                    </div>
                    <div class="relative z-10 w-full sm:w-1/2 flex justify-center sm:justify-end gap-4">
                        <div v-for="(product, idx) in newArrivals.slice(0, 2)" :key="product.id" class="w-40 sm:w-48 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-3 transform transition-transform hover:-translate-y-2 hover:shadow-2xl hover:bg-white/20" :class="idx === 1 ? 'mt-8' : ''">
                            <div class="aspect-square rounded-xl overflow-hidden bg-slate-800 mb-3 relative">
                                <img v-if="product.photo_url" :src="product.photo_url" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full items-center justify-center text-2xl text-slate-600">✦</div>
                            </div>
                            <h4 class="text-white font-semibold text-sm truncate">{{ product.name }}</h4>
                            <p class="text-pink-300 font-bold text-sm mt-1">₱{{ Number(product.price).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
