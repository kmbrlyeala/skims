<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard" :nav-items="[
        { routeName: 'dashboard', label: 'Dashboard', icon: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' },
        { routeName: 'admin.products.index', label: 'Products', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z' },
        { routeName: 'admin.categories.index', label: 'Categories', icon: 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z' }
    ]">
        <Head title="Dashboard" />

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 font-sans tracking-tight">Dashboard</h1>
                <p class="mt-1 text-sm font-medium text-gray-500">Here's what's happening with your store today.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-pink">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    May 20, 2026 - May 26, 2026
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
            </div>
        </div>

        <!-- 4 Stat Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-pink-light text-brand-pink flex-shrink-0">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-1.08 2.4-1.73 0-2.01-2.65-1.8-2.65-3.69 0-1.25.96-2.15 2.18-2.47V6h2.67v1.95c1.29.28 2.38 1.13 2.53 2.64h-1.95c-.15-.93-.84-1.43-2.18-1.43-1.61 0-2.06 1.05-2.06 1.63 0 1.96 2.65 1.83 2.65 3.82 0 1.25-1.02 2.25-2.26 2.48z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Sales</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900">₱124,580.00</p>
                    <p class="mt-1 flex items-center text-xs font-bold text-emerald-500">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                        12.5% <span class="text-gray-400 ml-1 font-medium">vs last week</span>
                    </p>
                </div>
            </div>

            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-pink-light text-brand-pink flex-shrink-0">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Orders</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900">256</p>
                    <p class="mt-1 flex items-center text-xs font-bold text-emerald-500">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                        8.2% <span class="text-gray-400 ml-1 font-medium">vs last week</span>
                    </p>
                </div>
            </div>

            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-pink-light text-brand-pink flex-shrink-0">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Customers</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900">1,428</p>
                    <p class="mt-1 flex items-center text-xs font-bold text-emerald-500">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                        15.3% <span class="text-gray-400 ml-1 font-medium">vs last week</span>
                    </p>
                </div>
            </div>

            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-pink-light text-brand-pink flex-shrink-0">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Low Stock Items</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900">12</p>
                    <p class="mt-1 flex items-center text-xs font-bold text-red-500">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                        5 <span class="text-gray-400 ml-1 font-medium">vs last week</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
            <!-- Sales Overview Chart Placeholder -->
            <div class="col-span-1 lg:col-span-2 rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex flex-col min-h-[350px]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Sales Overview</h2>
                    <select class="border-gray-200 rounded-lg text-xs font-bold text-gray-600 focus:ring-brand-pink focus:border-brand-pink shadow-sm">
                        <option>This Week</option>
                        <option>This Month</option>
                    </select>
                </div>
                <div class="flex items-center gap-4 text-xs font-bold text-gray-500 mb-4">
                    <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-brand-pink"></div> This Week</div>
                    <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full border-2 border-brand-pink-light"></div> Last Week</div>
                </div>
                <!-- Line Chart Placeholder -->
                <div class="relative flex-1 w-full flex items-end justify-between px-2 pt-10 border-l border-b border-gray-100">
                    <!-- Y Axis Labels -->
                    <div class="absolute -left-10 flex flex-col justify-between h-full py-2 text-[10px] font-bold text-gray-400">
                        <span>₱80K</span><span>₱60K</span><span>₱40K</span><span>₱20K</span><span>₱0</span>
                    </div>
                    <!-- Fake Line graph SVG -->
                    <div class="absolute inset-0 z-10 w-full h-full p-2">
                        <svg viewBox="0 0 100 50" class="w-full h-full preserve-aspect-ratio-none" preserveAspectRatio="none">
                            <!-- Background gradient for the area under line -->
                            <defs>
                                <linearGradient id="gradientPink" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f36a8d" stop-opacity="0.3" />
                                    <stop offset="100%" stop-color="#f36a8d" stop-opacity="0" />
                                </linearGradient>
                            </defs>
                            <path d="M0,50 L0,30 C10,20 20,40 30,35 C40,30 50,25 60,15 C70,5 80,30 90,20 C95,15 100,10 100,10 L100,50 Z" fill="url(#gradientPink)" />
                            <!-- Solid Line -->
                            <path d="M0,30 C10,20 20,40 30,35 C40,30 50,25 60,15 C70,5 80,30 90,20 C95,15 100,10 100,10" fill="none" stroke="#f36a8d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <!-- Last Week Dotted Line -->
                            <path d="M0,40 C20,30 40,45 60,35 C80,25 90,40 100,30" fill="none" stroke="#fdf2f5" stroke-width="2" stroke-dasharray="2 2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <!-- X Axis Labels -->
                    <div class="absolute -bottom-6 flex w-full justify-between text-[10px] font-bold text-gray-400 uppercase">
                        <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                    </div>
                </div>
            </div>

            <!-- Orders Status Donut Chart Placeholder -->
            <div class="col-span-1 rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100 flex flex-col min-h-[350px]">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Orders Status</h2>
                
                <div class="flex-1 flex flex-col items-center justify-center">
                    <!-- Donut Chart -->
                    <div class="relative h-48 w-48 mb-8">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#fdf2f5" stroke-width="8"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f36a8d" stroke-width="8" stroke-dasharray="40 60"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#fbbf24" stroke-width="8" stroke-dasharray="25 75" stroke-dashoffset="-40"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#34d399" stroke-width="8" stroke-dasharray="15 85" stroke-dashoffset="-65"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#a78bfa" stroke-width="8" stroke-dasharray="20 80" stroke-dashoffset="-80"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-extrabold text-gray-900">256</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</span>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="w-full space-y-3 px-4">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-yellow-400"></div> Pending</div>
                            <div class="text-gray-900">68 <span class="text-gray-400 font-medium">(26.6%)</span></div>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-brand-pink"></div> Processing</div>
                            <div class="text-gray-900">92 <span class="text-gray-400 font-medium">(35.9%)</span></div>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-purple-400"></div> Shipped</div>
                            <div class="text-gray-900">72 <span class="text-gray-400 font-medium">(28.1%)</span></div>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-emerald-400"></div> Delivered</div>
                            <div class="text-gray-900">24 <span class="text-gray-400 font-medium">(9.4%)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent Orders Table -->
            <div class="col-span-1 lg:col-span-2 rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
                    <Link :href="route('dashboard')" class="text-xs font-bold text-brand-pink border border-brand-pink-light bg-brand-pink-light px-3 py-1 rounded-full hover:bg-brand-pink hover:text-white transition">View All</Link>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 font-bold rounded-l-lg">Order</th>
                                <th class="px-4 py-3 font-bold">Customer</th>
                                <th class="px-4 py-3 font-bold">Total</th>
                                <th class="px-4 py-3 font-bold rounded-r-lg text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-4 font-bold text-gray-900">#SS1025</td>
                                <td class="px-4 py-4 font-medium text-gray-700">Jane Cooper</td>
                                <td class="px-4 py-4 font-bold text-gray-900">₱1,250.00</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-orange-600 uppercase tracking-wider">Processing</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-4 font-bold text-gray-900">#SS1024</td>
                                <td class="px-4 py-4 font-medium text-gray-700">Esther Howard</td>
                                <td class="px-4 py-4 font-bold text-gray-900">₱2,450.00</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider">Shipped</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-4 font-bold text-gray-900">#SS1023</td>
                                <td class="px-4 py-4 font-medium text-gray-700">Cameron Williamson</td>
                                <td class="px-4 py-4 font-bold text-gray-900">₱980.00</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Delivered</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-4 font-bold text-gray-900">#SS1022</td>
                                <td class="px-4 py-4 font-medium text-gray-700">Brooklyn Simmons</td>
                                <td class="px-4 py-4 font-bold text-gray-900">₱1,760.00</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-1 text-[10px] font-bold text-yellow-600 uppercase tracking-wider">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Top Products -->
            <div class="col-span-1 space-y-6">
                <!-- Quick Actions Grid -->
                <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">Add Product</span>
                        </Link>
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">Add Category</span>
                        </Link>
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">Add Coupon</span>
                        </Link>
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">Manage Orders</span>
                        </Link>
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">View Reports</span>
                        </Link>
                        <Link :href="route('dashboard')" class="flex flex-col items-center justify-center p-3 bg-brand-pink-light rounded-xl hover:bg-brand-pink group transition">
                            <svg class="h-6 w-6 text-brand-pink group-hover:text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            <span class="text-[9px] font-bold text-brand-pink uppercase tracking-widest text-center group-hover:text-white">Manage Users</span>
                        </Link>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div class="rounded-[1.5rem] bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Top Selling Products</h2>
                        <Link :href="route('dashboard')" class="text-xs font-bold text-brand-pink border border-brand-pink-light bg-brand-pink-light px-3 py-1 rounded-full hover:bg-brand-pink hover:text-white transition">View All</Link>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-brand-pink-light rounded-xl overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/hero_image.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Hydrating Glow Serum</p>
                                    <p class="text-xs font-medium text-gray-500">124 sold</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900">₱24,800.00</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-brand-pink-light rounded-xl overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/cat_makeup.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Niacinamide Toner</p>
                                    <p class="text-xs font-medium text-gray-500">98 sold</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900">₱19,600.00</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-brand-pink-light rounded-xl overflow-hidden p-1 flex-shrink-0">
                                    <img src="/images/cat_skincare.png" class="w-full h-full object-cover mix-blend-multiply" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Sunscreen SPF 50 PA+++</p>
                                    <p class="text-xs font-medium text-gray-500">87 sold</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900">₱17,400.00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
