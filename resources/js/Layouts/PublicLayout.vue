<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const cartCount = computed(() => page.props.cartCount || 0);
const flash = computed(() => page.props.flash || {});

const showMobileMenu = ref(false);
const isCartOpen = ref(false); // For Cart Drawer

// Auto-dismiss flash messages
const visibleFlash = ref({ success: null, error: null });
let flashTimer = null;

watch(flash, (val) => {
    if (val.success || val.error) {
        visibleFlash.value = { ...val };
        clearTimeout(flashTimer);
        flashTimer = setTimeout(() => {
            visibleFlash.value = { success: null, error: null };
        }, 3000);
    }
}, { immediate: true });

const dismissFlash = () => {
    visibleFlash.value = { success: null, error: null };
    clearTimeout(flashTimer);
};

const logout = () => {
    router.post(route('logout'));
};

const isActive = (routeName) => {
    try { return route().current(routeName); } catch { return false; }
};

const toggleCart = () => {
    isCartOpen.value = !isCartOpen.value;
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white text-gray-800 font-sans">
        <Head :title="title" />

        <!-- Flash Messages -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-x-full opacity-0"
            enter-to-class="transform translate-x-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-x-0 opacity-100"
            leave-to-class="transform translate-x-full opacity-0"
        >
            <div v-if="visibleFlash.success" class="fixed right-4 top-4 z-[70] max-w-sm">
                <div class="flex items-center gap-3 pr-2 shadow-lg bg-emerald-500 text-white p-4 rounded-xl">
                    <span>{{ visibleFlash.success }}</span>
                    <button @click="dismissFlash" class="ml-auto rounded-lg p-1 text-white hover:bg-emerald-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Navigation Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <!-- Top Header Row -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    
                    <!-- Mobile Menu Button -->
                    <div class="flex items-center md:hidden">
                        <button @click="showMobileMenu = !showMobileMenu" class="text-gray-500 hover:text-brand-pink focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <Link :href="route('home')" class="flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-pink-light text-brand-pink font-sans font-bold text-2xl leading-none rotate-3">
                                <svg class="w-6 h-6 -rotate-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </div>
                            <span class="font-sans text-2xl font-extrabold text-gray-900 tracking-tight">Skim<span class="text-brand-pink font-light">Shop</span></span>
                        </Link>
                    </div>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <input type="text" class="block w-full pl-11 pr-3 py-3 border-0 bg-gray-50 rounded-full text-sm placeholder-gray-400 focus:ring-2 focus:ring-brand-pink focus:bg-white transition-all shadow-inner" placeholder="Search for products, categories...">
                        </div>
                    </div>

                    <!-- Right Icons -->
                    <div class="flex items-center space-x-6 text-gray-700">
                        
                        <!-- Auth Section -->
                        <template v-if="user">
                            <div class="relative group">
                                <Link :href="route('customer.dashboard')" class="hover:text-brand-pink transition block pb-1">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </Link>
                                
                                <!-- Dropdown for Auth -->
                                <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50 hidden group-hover:block transition-all">
                                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-2">
                                        Welcome, {{ user.name }}
                                    </div>
                                    <Link :href="route('customer.dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-pink-light hover:text-brand-pink">My Account</Link>
                                    <Link :href="route('customer.orders')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-pink-light hover:text-brand-pink">Orders</Link>
                                    <button @click="logout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-brand-pink-light hover:text-brand-pink">Logout</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="flex items-center gap-1 text-sm font-bold text-gray-700 hover:text-brand-pink transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Login
                            </Link>
                            <Link :href="route('register')" class="px-4 py-2 bg-brand-pink-light text-brand-pink text-sm font-bold rounded-full hover:bg-brand-pink hover:text-white transition">
                                Register
                            </Link>
                        </template>

                        <!-- Cart Icon with slide-out toggle -->
                        <button @click="toggleCart" class="hover:text-brand-pink transition relative">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span v-if="cartCount > 0" class="absolute -top-1 -right-2 bg-brand-pink text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center ring-2 ring-white">
                                {{ cartCount }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Secondary Navigation Row -->
            <div class="hidden md:block border-t border-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex space-x-10 items-center justify-center py-3 font-semibold text-sm text-gray-600">
                        <Link :href="route('home')" :class="isActive('home') ? 'text-brand-pink' : 'hover:text-brand-pink transition'">Home</Link>
                        <Link :href="route('customer.shop')" :class="isActive('shop') ? 'text-brand-pink' : 'hover:text-brand-pink transition'">Shop</Link>
                        <Link :href="route('customer.shop', { search: 'categories' })" class="hover:text-brand-pink transition">Categories</Link>
                        <Link :href="route('customer.shop')" class="hover:text-brand-pink transition">Bestsellers</Link>
                        <Link :href="route('customer.shop')" class="hover:text-brand-pink transition">New Arrivals</Link>
                        <Link :href="route('customer.shop')" class="flex items-center hover:text-brand-pink transition">
                            Promos
                            <span class="ml-2 bg-orange-400 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full uppercase">Hot</span>
                        </Link>
                        <Link href="#" class="hover:text-brand-pink transition ml-auto">About</Link>
                        <Link href="#" class="hover:text-brand-pink transition">Contact</Link>
                    </nav>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="showMobileMenu" class="md:hidden bg-white border-t border-gray-100">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <input type="text" class="block w-full mb-4 px-4 py-2 border-0 bg-gray-50 rounded-lg text-sm" placeholder="Search...">
                    <Link :href="route('home')" class="block px-3 py-2 text-base font-bold text-gray-900 hover:bg-brand-pink-light hover:text-brand-pink rounded-lg">Home</Link>
                    <Link :href="route('customer.shop')" class="block px-3 py-2 text-base font-bold text-gray-900 hover:bg-brand-pink-light hover:text-brand-pink rounded-lg">Shop</Link>
                    <Link :href="route('customer.dashboard')" class="block px-3 py-2 text-base font-bold text-gray-900 hover:bg-brand-pink-light hover:text-brand-pink rounded-lg">My Account</Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow w-full">
            <slot />
        </main>

        <!-- Slide-out Cart Drawer -->
        <Teleport to="body">
            <!-- Overlay -->
            <Transition
                enter-active-class="transition-opacity ease-linear duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity ease-linear duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isCartOpen" @click="isCartOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60]" />
            </Transition>

            <!-- Drawer -->
            <Transition
                enter-active-class="transition ease-in-out duration-300 transform"
                enter-from-class="translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transition ease-in-out duration-300 transform"
                leave-from-class="translate-x-0"
                leave-to-class="translate-x-full"
            >
                <div v-if="isCartOpen" class="fixed inset-y-0 right-0 z-[60] flex w-full max-w-sm flex-col bg-white shadow-2xl">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Your Cart ({{ cartCount }})</h2>
                        <button @click="isCartOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-6 text-center text-gray-500">
                        <!-- Placeholder for Cart Items -->
                        <div v-if="cartCount === 0" class="flex flex-col items-center justify-center h-full">
                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                            </svg>
                            <p>Your cart is empty.</p>
                            <button @click="isCartOpen = false; router.get(route('customer.shop'))" class="mt-6 px-6 py-2 bg-brand-pink text-white rounded-xl font-bold text-sm hover:bg-brand-pink-hover transition">Continue Shopping</button>
                        </div>
                        <div v-else class="text-left">
                            <p class="mb-4 text-sm font-medium">Items in your cart...</p>
                            <!-- Summary -->
                            <div class="mt-8 border-t border-gray-100 pt-6">
                                <Link :href="route('customer.cart')" @click="isCartOpen = false" class="block w-full py-4 text-center bg-brand-pink text-white rounded-2xl font-bold hover:bg-brand-pink-hover transition shadow-md">
                                    Checkout
                                </Link>
                                <button @click="isCartOpen = false" class="block w-full py-3 mt-3 text-center text-brand-pink font-bold text-sm hover:bg-brand-pink-light rounded-xl transition">
                                    Continue Shopping
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                    <div class="col-span-2 md:col-span-1">
                        <Link :href="route('home')" class="flex items-center gap-2 mb-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-pink-light text-brand-pink font-sans font-bold text-lg leading-none rotate-3">
                                <svg class="w-5 h-5 -rotate-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </div>
                            <span class="font-sans text-xl font-extrabold text-gray-900 tracking-tight">Skim<span class="text-brand-pink font-light">Shop</span></span>
                        </Link>
                        <p class="text-sm text-gray-500 mb-4">Discover skincare that enhances your natural beauty, gentle, effective, and made for you.</p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold mb-4 text-gray-900">Quick Links</h4>
                        <ul class="space-y-3 text-sm text-gray-500 font-medium">
                            <li><Link :href="route('customer.shop')" class="hover:text-brand-pink transition">Shop All</Link></li>
                            <li><Link href="#" class="hover:text-brand-pink transition">About Us</Link></li>
                            <li><Link href="#" class="hover:text-brand-pink transition">Contact</Link></li>
                            <li><Link href="#" class="hover:text-brand-pink transition">FAQ</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4 text-gray-900">Policies</h4>
                        <ul class="space-y-3 text-sm text-gray-500 font-medium">
                            <li><Link href="#" class="hover:text-brand-pink transition">Shipping & Returns</Link></li>
                            <li><Link href="#" class="hover:text-brand-pink transition">Privacy Policy</Link></li>
                            <li><Link href="#" class="hover:text-brand-pink transition">Terms of Service</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold mb-4 text-gray-900">Newsletter</h4>
                        <p class="text-sm text-gray-500 mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
                        <div class="flex">
                            <input type="email" placeholder="Enter your email" class="w-full px-4 py-2 bg-gray-50 border-0 rounded-l-xl focus:ring-2 focus:ring-brand-pink text-sm shadow-inner" />
                            <button class="bg-brand-pink text-white px-4 py-2 rounded-r-xl text-sm font-bold hover:bg-brand-pink-hover transition shadow-md">Subscribe</button>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 pt-8 mt-8 flex flex-wrap justify-between items-center text-sm font-medium text-gray-400">
                    <p>&copy; 2026 SkimShop. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
