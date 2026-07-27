<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const wishlistItems = ref([
    {
        id: 1,
        name: 'Radiant Glow Serum',
        category: 'Skincare',
        price: '₱1,250.00',
        image: '/storage/products/serum.png',
        inStock: true
    },
    {
        id: 2,
        name: 'Hydrating Rose Toner',
        category: 'Skincare',
        price: '₱850.00',
        image: '/storage/products/toner.png',
        inStock: false
    },
    {
        id: 3,
        name: 'Velvet Matte Lipstick - Blossom',
        category: 'Makeup',
        price: '₱650.00',
        image: '/storage/products/lipstick.png',
        inStock: true
    },
    {
        id: 4,
        name: 'Nourishing Hair Mask',
        category: 'Haircare',
        price: '₱1,500.00',
        image: '/storage/products/hairmask.png',
        inStock: true
    }
]);

const removeItem = (id) => {
    wishlistItems.value = wishlistItems.value.filter(item => item.id !== id);
};
</script>

<template>
    <CustomerLayout title="Wishlist">
        <Head title="My Wishlist" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Wishlist</h1>
                <p class="mt-2 text-sm text-gray-500">Items you've saved for later. Keep an eye out for sales!</p>
            </div>

            <!-- Empty State -->
            <div v-if="wishlistItems.length === 0" class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-16 flex flex-col items-center justify-center text-center">
                <div class="h-24 w-24 bg-brand-pink-light rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <svg class="h-10 w-10 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Looks like you haven't saved any products yet. Discover your new skincare favorites today!</p>
                <Link :href="route('customer.shop')" class="px-8 py-3 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-lg hover:shadow-brand-pink/30 hover:-translate-y-0.5">
                    Start Shopping
                </Link>
            </div>

            <!-- Wishlist Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="item in wishlistItems" :key="item.id" class="bg-white/60 backdrop-blur-xl border border-white/40 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group flex flex-col relative hover:-translate-y-1">
                    
                    <!-- Remove Button -->
                    <button @click="removeItem(item.id)" class="absolute top-4 right-4 z-10 bg-white/80 backdrop-blur text-gray-400 hover:text-red-500 p-2 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Image Section -->
                    <div class="h-56 bg-brand-pink-light/30 relative overflow-hidden flex items-center justify-center p-6 group">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-pink-light/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Fallback visual if no image -->
                        <div class="h-32 w-32 bg-white rounded-2xl shadow-sm rotate-3 group-hover:rotate-0 transition-transform duration-500 flex items-center justify-center border border-gray-100">
                            <span class="text-brand-pink text-opacity-50 font-bold text-4xl">Skim</span>
                        </div>

                        <!-- Out of Stock Overlay -->
                        <div v-if="!item.inStock" class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center">
                            <span class="px-4 py-1.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-lg">Out of Stock</span>
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6 flex flex-col flex-grow">
                        <span class="text-[10px] font-bold text-brand-pink uppercase tracking-widest mb-1">{{ item.category }}</span>
                        <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 line-clamp-2">{{ item.name }}</h3>
                        <p class="text-gray-900 font-extrabold text-lg mt-auto">{{ item.price }}</p>
                        
                        <!-- Action Button -->
                        <button 
                            class="mt-5 w-full py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2"
                            :class="item.inStock ? 'bg-brand-pink text-white hover:bg-brand-pink-hover shadow-md hover:shadow-brand-pink/30' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            :disabled="!item.inStock"
                        >
                            <svg v-if="item.inStock" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ item.inStock ? 'Add to Cart' : 'Notify Me' }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
