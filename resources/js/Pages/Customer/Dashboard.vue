<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="page-container space-y-8">
            <!-- Welcome Header -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Welcome, Hannah!</h1>
                <p class="mt-1 text-sm text-slate-500">Here's a quick overview of your account.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-6 sm:grid-cols-3">
                <div class="glass-card flex flex-col items-center justify-center text-center p-6 transition-all hover:bg-slate-50 cursor-pointer">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">My Orders</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-pink-600">5</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6 transition-all hover:bg-slate-50 cursor-pointer">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Wishlist</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-violet-600">12</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6 transition-all hover:bg-slate-50 cursor-pointer">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cart</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-slate-700">3</dd>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Recommended Products -->
                <div class="glass-card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recommended Products</h2>
                    </div>
                    <ul role="list" class="divide-y divide-slate-100">
                        <li class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-pink-50 text-xl shadow-sm">
                                    🧴
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Hydrating Moisturizer</p>
                                    <p class="text-xs text-slate-500">₱450.00</p>
                                </div>
                            </div>
                            <button class="rounded-full bg-slate-100 p-2 text-slate-400 hover:bg-pink-100 hover:text-pink-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                            </button>
                        </li>
                        <li class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-50 text-xl shadow-sm">
                                    ✨
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Vitamin C Serum</p>
                                    <p class="text-xs text-slate-500">₱850.00</p>
                                </div>
                            </div>
                            <button class="rounded-full bg-slate-100 p-2 text-slate-400 hover:bg-pink-100 hover:text-pink-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                            </button>
                        </li>
                        <li class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-xl shadow-sm">
                                    ☀️
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Sunscreen SPF 50</p>
                                    <p class="text-xs text-slate-500">₱350.00</p>
                                </div>
                            </div>
                            <button class="rounded-full bg-slate-100 p-2 text-slate-400 hover:bg-pink-100 hover:text-pink-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Recent Orders -->
                <div class="glass-card flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent Orders</h2>
                        <a href="#" class="text-xs font-medium text-pink-600 hover:text-pink-500">View All</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="whitespace-nowrap py-3 pr-3 text-slate-700 font-medium">#1025</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-slate-700">Moisturizer</td>
                                    <td class="whitespace-nowrap pl-3 py-3 text-right"><span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Shipped</span></td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap py-3 pr-3 text-slate-700 font-medium">#1024</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-slate-700">Toner</td>
                                    <td class="whitespace-nowrap pl-3 py-3 text-right"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap py-3 pr-3 text-slate-700 font-medium">#1023</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-slate-700">Serum</td>
                                    <td class="whitespace-nowrap pl-3 py-3 text-right"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
