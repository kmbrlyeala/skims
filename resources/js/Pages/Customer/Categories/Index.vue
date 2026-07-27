<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const categories = [
    {
        id: 1,
        name: 'Skincare',
        description: 'Serums, moisturizers, and cleansers for a radiant glow.',
        image: 'linear-gradient(to bottom right, #fdfbfb, #ebedee)',
        icon: 'M14.25 7.75v-.546a3.985 3.985 0 0 0-3-3.83v-.06a.75.75 0 0 0-1.5 0v.06a3.985 3.985 0 0 0-3 3.83v.546c0 .762-.258 1.487-.714 2.062a4.5 4.5 0 0 1-1.396 1.144C3.89 11.442 3 12.393 3 13.5c0 1.93 1.57 3.5 3.5 3.5h11c1.93 0 3.5-1.57 3.5-3.5 0-1.107-.89-2.058-1.64-2.502a4.5 4.5 0 0 1-1.396-1.144A3.985 3.985 0 0 0 14.25 7.75Z'
    },
    {
        id: 2,
        name: 'Makeup',
        description: 'Foundations, lipsticks, and blushes for every occasion.',
        image: 'linear-gradient(to bottom right, #fdfbfb, #f3e7e9)',
        icon: 'M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5'
    },
    {
        id: 3,
        name: 'Haircare',
        description: 'Shampoos, masks, and oils for luscious locks.',
        image: 'linear-gradient(to bottom right, #fdfbfb, #e0c3fc)',
        icon: 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'
    },
    {
        id: 4,
        name: 'Fragrance',
        description: 'Perfumes and mists with captivating scents.',
        image: 'linear-gradient(to bottom right, #fdfbfb, #a1c4fd)',
        icon: 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.563.563 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'
    }
];
</script>

<template>
    <CustomerLayout title="Categories">
        <Head title="Browse Categories" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            
            <!-- Header -->
            <div class="flex items-end justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Categories</h1>
                    <p class="mt-2 text-sm text-gray-500">Explore our wide range of premium beauty collections.</p>
                </div>
                <Link :href="route('customer.shop')" class="text-brand-pink font-bold text-sm hover:text-brand-pink-hover transition flex items-center gap-1">
                    Shop All Products
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Link 
                    v-for="category in categories" 
                    :key="category.id" 
                    :href="route('customer.shop', { category: category.name })"
                    class="group relative h-64 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-white/50"
                >
                    <!-- Background with Gradient -->
                    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" :style="{ background: category.image }"></div>
                    
                    <!-- Decorative Circle -->
                    <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-white/20 blur-2xl group-hover:bg-brand-pink/20 transition-colors duration-500"></div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="h-12 w-12 bg-white/60 backdrop-blur-md rounded-2xl flex items-center justify-center mb-auto shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="category.icon" />
                            </svg>
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">{{ category.name }}</h3>
                        <p class="text-sm text-gray-600 max-w-sm">{{ category.description }}</p>
                    </div>
                </Link>
            </div>
            
            <!-- Trending Banner -->
            <div class="mt-12 bg-gradient-to-r from-brand-pink-light to-white rounded-3xl p-8 md:p-12 shadow-inner border border-brand-pink/10 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-white/50 to-transparent"></div>
                <div class="relative z-10 max-w-xl text-center md:text-left">
                    <span class="inline-block px-3 py-1 bg-brand-pink text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-4">Trending Now</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">The Summer Glow Kit</h2>
                    <p class="text-gray-600 mb-6">Everything you need to maintain that perfect radiant look all season long. Save 20% when you buy the bundle.</p>
                    <Link :href="route('customer.shop')" class="inline-flex px-8 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-brand-pink transition shadow-lg">
                        Shop the Kit
                    </Link>
                </div>
                <!-- Decorative Graphic -->
                <div class="hidden md:flex relative z-10 h-40 w-40 bg-white/80 backdrop-blur-sm rounded-full items-center justify-center shadow-2xl rotate-12">
                    <span class="text-brand-pink text-opacity-40 font-bold text-5xl">-20%</span>
                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
