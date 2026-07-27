<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Admin Dashboard">
        <div class="page-container space-y-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Overview of system activity</p>
            </div>

            <!-- Stats Grid 1 -->
            <div class="grid gap-6 sm:grid-cols-4">
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Sales</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-pink-600">₱120,000</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Orders</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-slate-700">120</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Products</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-slate-700">48</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Customers</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-slate-700">320</dd>
                </div>
            </div>

            <!-- Stats Grid 2 -->
            <div class="grid gap-6 sm:grid-cols-4">
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Low Stock</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-amber-600">5</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending PRs</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-pink-600">3</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Orders</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-blue-600">12</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Suppliers</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-slate-700">8</dd>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Recent Orders -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Recent Orders</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1025</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700">Hannah Sadicon</td>
                                    <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Paid</span></td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-500">To Ship</td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1024</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700">Maria Santos</td>
                                    <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Shipped</span></td>
                                    <td class="whitespace-nowrap px-3 py-4"></td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1023</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700">John Cruz</td>
                                    <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                                    <td class="whitespace-nowrap px-3 py-4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Low Stock Products</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Moisturizer</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-amber-600 font-semibold">5 pcs</td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Vitamin Serum</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-amber-600 font-semibold">3 pcs</td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Sunscreen</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-red-600 font-semibold">2 pcs</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
