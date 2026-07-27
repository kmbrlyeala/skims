<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <CustomerLayout title="My Dashboard">
        <Head title="My Dashboard" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 font-sans tracking-tight">Good Morning, Faye! 👋</h1>
                    <p class="mt-2 text-sm font-medium text-gray-500">Welcome back to SkimShop. We're happy to see you!</p>
                </div>
                
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between gap-8 md:min-w-[250px]">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Your Points</p>
                        <p class="text-xl font-black text-gray-900">650 Points</p>
                    </div>
                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-brand-pink text-white shadow-md shadow-pink-500/20">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1h-4v-2h4zM9 4c.55 0 1 .45 1 1h-4c0-.55.45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group cursor-pointer hover:shadow-md transition">
                    <div class="h-12 w-12 rounded-2xl bg-brand-pink-light text-brand-pink flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mb-1">3</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Orders In Progress</p>
                </div>
                
                <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group cursor-pointer hover:shadow-md transition">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mb-1">2</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Orders Delivered</p>
                </div>

                <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group cursor-pointer hover:shadow-md transition">
                    <div class="h-12 w-12 rounded-2xl bg-brand-pink-light text-brand-pink flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mb-1">5</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Wishlist Items</p>
                </div>

                <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group cursor-pointer hover:shadow-md transition">
                    <div class="h-12 w-12 rounded-2xl bg-brand-pink-light text-brand-pink flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mb-1">3</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Coupons Available</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Orders -->
                <div class="bg-white rounded-[1.5rem] p-6 sm:p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
                        <Link :href="route('customer.orders')" class="text-xs font-bold text-brand-pink hover:text-brand-pink-hover transition">View All Orders &rsaquo;</Link>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-pink-light overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/cat_skincare.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Order #SS1025</p>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5">May 20, 2026</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-orange-600 uppercase tracking-wider mx-2">Processing</span>
                            <span class="text-sm font-bold text-gray-900">₱2,450.00</span>
                        </div>
                        
                        <div class="flex items-center justify-between border-b border-gray-50 pb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-pink-light overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/cat_makeup.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Order #SS1024</p>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5">May 18, 2026</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider mx-2">Shipped</span>
                            <span class="text-sm font-bold text-gray-900">₱1,250.00</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-pink-light overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/cat_haircare.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Order #SS1023</p>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5">May 15, 2026</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600 uppercase tracking-wider mx-2">Delivered</span>
                            <span class="text-sm font-bold text-gray-900">₱980.00</span>
                        </div>
                    </div>
                </div>

                <!-- Recommended For You -->
                <div class="bg-white rounded-[1.5rem] p-6 sm:p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Recommended For You</h2>
                        <Link :href="route('customer.shop')" class="text-xs font-bold text-brand-pink hover:text-brand-pink-hover transition">View All &rsaquo;</Link>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <Link :href="route('customer.shop')" class="group text-center">
                            <div class="aspect-[4/5] w-full rounded-2xl bg-brand-pink-light mb-3 p-3 flex items-center justify-center relative overflow-hidden group-hover:shadow-md transition">
                                <img src="/images/cat_skincare.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                            </div>
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-1 mb-1 group-hover:text-brand-pink transition">Vitamin C Serum</h3>
                            <div class="flex items-center justify-center text-yellow-400 text-[10px] mb-1">
                                ★★★★★ <span class="text-gray-400 font-medium ml-1">(64)</span>
                            </div>
                            <p class="text-sm font-black text-gray-900">₱1,180.00</p>
                            <button class="w-full mt-2 bg-brand-pink-light text-brand-pink font-bold text-xs py-2 rounded-xl group-hover:bg-brand-pink group-hover:text-white transition">Add to Cart</button>
                        </Link>
                        
                        <Link :href="route('customer.shop')" class="group text-center">
                            <div class="aspect-[4/5] w-full rounded-2xl bg-brand-pink-light mb-3 p-3 flex items-center justify-center relative overflow-hidden group-hover:shadow-md transition">
                                <img src="/images/cat_makeup.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                            </div>
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-1 mb-1 group-hover:text-brand-pink transition">Brightening Cream</h3>
                            <div class="flex items-center justify-center text-yellow-400 text-[10px] mb-1">
                                ★★★★★ <span class="text-gray-400 font-medium ml-1">(82)</span>
                            </div>
                            <p class="text-sm font-black text-gray-900">₱830.00</p>
                            <button class="w-full mt-2 bg-brand-pink-light text-brand-pink font-bold text-xs py-2 rounded-xl group-hover:bg-brand-pink group-hover:text-white transition">Add to Cart</button>
                        </Link>

                        <Link :href="route('customer.shop')" class="group text-center">
                            <div class="aspect-[4/5] w-full rounded-2xl bg-brand-pink-light mb-3 p-3 flex items-center justify-center relative overflow-hidden group-hover:shadow-md transition">
                                <img src="/images/cat_haircare.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500" />
                            </div>
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-1 mb-1 group-hover:text-brand-pink transition">Rose Water Toner</h3>
                            <div class="flex items-center justify-center text-yellow-400 text-[10px] mb-1">
                                ★★★★☆ <span class="text-gray-400 font-medium ml-1">(38)</span>
                            </div>
                            <p class="text-sm font-black text-gray-900">₱890.00</p>
                            <button class="w-full mt-2 bg-brand-pink-light text-brand-pink font-bold text-xs py-2 rounded-xl group-hover:bg-brand-pink group-hover:text-white transition">Add to Cart</button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Promo Banner & Recently Viewed -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Promo Banner -->
                <div class="col-span-1 rounded-[1.5rem] bg-gradient-to-br from-brand-pink-light to-brand-pink overflow-hidden relative p-8 shadow-sm border border-brand-pink/20 flex flex-col justify-center min-h-[200px]">
                    <!-- Decorative blur -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight leading-tight mb-2">Glow More, Save More!</h2>
                        <p class="text-sm font-medium text-gray-800 mb-6">Get 20% off on orders over ₱2,000</p>
                        <Link :href="route('customer.shop')" class="inline-block bg-white text-brand-pink px-6 py-2.5 rounded-full font-bold text-xs shadow-md hover:shadow-lg transition">Shop Now</Link>
                    </div>
                </div>

                <!-- Recently Viewed -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-gray-900">Recently Viewed</h2>
                        <Link :href="route('customer.shop')" class="text-xs font-bold text-brand-pink hover:text-brand-pink-hover transition">View All &rsaquo;</Link>
                    </div>
                    <div class="flex items-center gap-4 overflow-x-auto pb-2">
                        <Link :href="route('customer.shop')" class="w-20 flex-shrink-0 group">
                            <div class="w-20 h-20 bg-brand-pink-light rounded-2xl p-2 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-brand-pink transition mb-2">
                                <img src="/images/cat_fragrance.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition" />
                            </div>
                        </Link>
                        <Link :href="route('customer.shop')" class="w-20 flex-shrink-0 group">
                            <div class="w-20 h-20 bg-brand-pink-light rounded-2xl p-2 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-brand-pink transition mb-2">
                                <img src="/images/cat_accessories.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition" />
                            </div>
                        </Link>
                        <Link :href="route('customer.shop')" class="w-20 flex-shrink-0 group">
                            <div class="w-20 h-20 bg-brand-pink-light rounded-2xl p-2 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-brand-pink transition mb-2">
                                <img src="/images/cat_skincare.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition" />
                            </div>
                        </Link>
                        <Link :href="route('customer.shop')" class="w-20 flex-shrink-0 group">
                            <div class="w-20 h-20 bg-brand-pink-light rounded-2xl p-2 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-brand-pink transition mb-2">
                                <img src="/images/cat_makeup.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition" />
                            </div>
                        </Link>
                        <Link :href="route('customer.shop')" class="w-20 flex-shrink-0 group">
                            <div class="w-20 h-20 bg-brand-pink-light rounded-2xl p-2 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-brand-pink transition mb-2">
                                <img src="/images/cat_haircare.png" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition" />
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
