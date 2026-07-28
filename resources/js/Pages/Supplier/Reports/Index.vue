<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Reports">
        <div class="page-container space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Performance Reports</h1>
                    <p class="mt-1 text-sm text-slate-500">Analyze your fulfillment rate and sales performance</p>
                </div>
                <div class="flex items-center gap-3">
                    <select class="form-input !w-auto !py-1.5 text-sm">
                        <option>Last 30 Days</option>
                        <option>This Quarter</option>
                        <option>This Year</option>
                    </select>
                    <button class="btn-secondary !py-1.5 text-sm flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Sales Report -->
                <div class="glass-card hover:shadow-md transition-shadow group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 rounded-full bg-pink-50 p-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">Total Sales</h3>
                            <span class="text-emerald-500 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded-md">+12.5%</span>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">₱142,500</p>
                        <p class="mt-1 text-xs text-slate-500">vs last period</p>
                    </div>
                </div>

                <!-- Fulfillment Rate -->
                <div class="glass-card hover:shadow-md transition-shadow group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 rounded-full bg-blue-50 p-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">Order Fulfillment Rate</h3>
                            <span class="text-emerald-500 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded-md">+3.2%</span>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">98.5%</p>
                        <p class="mt-1 text-xs text-slate-500">Orders fully delivered on time</p>
                    </div>
                </div>

                <!-- Return / Reject Rate -->
                <div class="glass-card hover:shadow-md transition-shadow group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 rounded-full bg-red-50 p-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">Discrepancy Rate</h3>
                            <span class="text-slate-400 font-bold text-xs bg-slate-50 px-2 py-0.5 rounded-md">Stable</span>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">1.2%</p>
                        <p class="mt-1 text-xs text-slate-500">Orders with missing/damaged items</p>
                    </div>
                </div>
            </div>

            <!-- Chart placeholder -->
            <div class="glass-card flex h-[350px] items-center justify-center text-slate-400">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <p class="text-sm font-semibold">Sales Chart Rendering Engine Not Found</p>
                    <p class="text-xs">Data available upon integration with Chart.js</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
