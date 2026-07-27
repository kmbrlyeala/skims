<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

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
    <PublicLayout title="SkimShop - Reveal Your Natural Glow">
        <div class="bg-white">
            
            <!-- Hero Section -->
            <div class="relative bg-brand-pink-light overflow-hidden rounded-b-[3rem] mx-2 mt-2 pb-12 pt-8 sm:pb-16 sm:pt-12 lg:pb-24 lg:pt-16 shadow-inner">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <!-- 20% OFF Badge -->
                    <div class="absolute right-10 top-10 hidden lg:flex h-32 w-32 items-center justify-center rounded-full bg-white shadow-xl z-20">
                        <div class="text-center">
                            <span class="block text-xl font-black text-brand-pink leading-none">20% OFF</span>
                            <span class="block text-xs font-bold text-gray-500 mt-1">For New<br>Customers</span>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-12 items-center relative z-10">
                        <div class="text-center lg:text-left">
                            <h1 class="text-5xl tracking-tight font-sans font-extrabold text-gray-900 sm:text-6xl md:text-7xl leading-tight">
                                <span class="block text-gray-800">Reveal Your</span>
                                <span class="block text-brand-pink mt-2">Natural <span class="text-gray-900">Glow</span></span>
                            </h1>
                            <p class="mt-6 text-base text-gray-600 sm:text-lg max-w-md mx-auto lg:mx-0 font-medium">
                                Discover skincare that enhances your natural beauty, gentle, effective, and made for you.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row gap-4 sm:justify-center lg:justify-start">
                                <Link :href="route('customer.shop')" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-full text-white bg-brand-pink hover:bg-brand-pink-hover shadow-lg shadow-pink-500/30 transition-all active:scale-95">
                                    Shop Now
                                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </Link>
                                <button class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-gray-700 bg-white rounded-full hover:text-brand-pink shadow-md hover:shadow-lg transition-all active:scale-95">
                                    <svg class="mr-2 h-5 w-5 text-brand-pink" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    Watch Video
                                </button>
                            </div>
                        </div>
                        
                        <!-- Hero Image -->
                        <div class="relative lg:h-[500px] flex justify-center items-center">
                            <!-- Decorative background shapes -->
                            <div class="absolute inset-0 bg-brand-pink opacity-10 rounded-full blur-3xl transform scale-75"></div>
                            <img class="relative z-10 object-contain max-h-[500px] drop-shadow-2xl" src="/images/hero_image.png" alt="SkimShop Model">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Bar -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4">
                <div class="flex flex-wrap justify-between items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Natural Ingredients</h4>
                            <p class="text-xs text-gray-500 font-medium">Safe & Organic</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Dermatologist Tested</h4>
                            <p class="text-xs text-gray-500 font-medium">Clinically Proven</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Cruelty Free</h4>
                            <p class="text-xs text-gray-500 font-medium">Love Animals</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-9.75V3m-9 9h9m-9 0a9 9 0 1118 0 9 9 0 01-18 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Secure Payment</h4>
                            <p class="text-xs text-gray-500 font-medium">100% Protected</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Fast Delivery</h4>
                            <p class="text-xs text-gray-500 font-medium">On Time</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop By Categories -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex justify-between items-end mb-10">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Shop By Categories</h2>
                    <Link :href="route('customer.shop')" class="text-sm font-bold text-brand-pink hover:text-brand-pink-hover border border-brand-pink px-4 py-1.5 rounded-full transition-all">View All</Link>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                    <Link :href="route('customer.shop', { search: 'cleanser' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_skincare.png" alt="Cleansers" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Cleansers</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">12 Products</p>
                    </Link>
                    <Link :href="route('customer.shop', { search: 'moisturizer' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_makeup.png" alt="Moisturizers" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Moisturizers</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">18 Products</p>
                    </Link>
                    <Link :href="route('customer.shop', { search: 'serum' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_haircare.png" alt="Serums" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Serums</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">15 Products</p>
                    </Link>
                    <Link :href="route('customer.shop', { search: 'sunscreen' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_accessories.png" alt="Sunscreen" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Sunscreen</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">10 Products</p>
                    </Link>
                    <Link :href="route('customer.shop', { search: 'mask' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_fragrance.png" alt="Masks" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Masks</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">14 Products</p>
                    </Link>
                    <Link :href="route('customer.shop', { search: 'toner' })" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-brand-pink-light mb-4 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:-translate-y-1">
                            <img src="/images/cat_skincare.png" alt="Toners" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Toners</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">12 Products</p>
                    </Link>
                </div>
            </div>

            <!-- Best Sellers (Featured Products) -->
            <div class="bg-gray-50 rounded-[3rem] mx-2 mb-12 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-end mb-10">
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Best Sellers</h2>
                        <Link :href="route('customer.shop')" class="text-sm font-bold text-brand-pink hover:text-brand-pink-hover border border-brand-pink px-4 py-1.5 rounded-full transition-all">View All</Link>
                    </div>
                    
                    <div v-if="featuredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="product in featuredProducts.slice(0, 4)" :key="product.id" class="group relative bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300">
                            <!-- Favorite Button -->
                            <button class="absolute top-6 right-6 z-10 h-8 w-8 bg-white rounded-full flex items-center justify-center text-gray-300 hover:text-brand-pink shadow-sm transition">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>

                            <Link :href="route('customer.shop', { search: product.name })" class="block">
                                <div class="aspect-[4/5] w-full overflow-hidden bg-brand-pink-light rounded-2xl relative mb-4 p-6">
                                    <img v-if="product.photo_url" :src="product.photo_url" :alt="product.name" class="h-full w-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500" />
                                    <div v-else class="flex h-full items-center justify-center text-4xl text-brand-pink opacity-20">✦</div>
                                    
                                    <!-- Badges -->
                                    <div v-if="product.stock < 1" class="absolute top-4 left-4 bg-gray-900 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Sold Out</div>
                                    <div v-else-if="Math.random() > 0.5" class="absolute top-4 left-4 bg-brand-pink text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Best Seller</div>
                                </div>
                                <div class="px-2">
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1 group-hover:text-brand-pink transition">
                                        {{ product.name }}
                                    </h3>
                                    <div class="flex items-center mt-1 text-yellow-400 text-xs">
                                        ★★★★★ <span class="text-gray-400 font-medium ml-1.5">(128 reviews)</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <p class="text-lg font-black text-gray-900">₱{{ Number(product.price).toFixed(2) }}</p>
                                        <button class="h-8 w-8 bg-brand-pink text-white rounded-full flex items-center justify-center hover:bg-brand-pink-hover shadow-md transition-transform active:scale-95">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div v-else class="text-center py-12 text-gray-400 font-medium">
                        No products found.
                    </div>
                </div>
            </div>

        </div>
    </PublicLayout>
</template>
