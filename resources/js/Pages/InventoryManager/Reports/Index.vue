<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Reports">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Reports</h1>
                <p class="mt-1 text-sm text-slate-500">Generate and view inventory analytics</p>
            </div>

            <!-- Available Reports -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="glass-card hover:bg-slate-50 cursor-pointer transition-colors">
                    <h3 class="font-bold text-slate-900">Inventory Valuation</h3>
                    <p class="mt-1 text-xs text-slate-500">Total value of current stock</p>
                </div>
                <div class="glass-card hover:bg-slate-50 cursor-pointer transition-colors">
                    <h3 class="font-bold text-slate-900">Stock Movement</h3>
                    <p class="mt-1 text-xs text-slate-500">Inflow and outflow summary</p>
                </div>
                <div class="glass-card hover:bg-slate-50 cursor-pointer transition-colors">
                    <h3 class="font-bold text-slate-900">Low Stock Items</h3>
                    <p class="mt-1 text-xs text-slate-500">Items near or below reorder pt</p>
                </div>
                <div class="glass-card hover:bg-slate-50 cursor-pointer transition-colors">
                    <h3 class="font-bold text-slate-900">Supplier Perform.</h3>
                    <p class="mt-1 text-xs text-slate-500">Delivery times and fulfillment</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Generate Custom Report -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Generate Custom Report</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Report Type</label>
                            <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                                <option>Inventory Valuation</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Date Range</label>
                            <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                                <option>2026-07-01 to 2026-07-31</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Category</label>
                            <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                                <option>All Categories</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Export Format</label>
                            <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                                <option>PDF</option>
                                <option>Excel / CSV</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">Generate Report</button>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Recent Reports (Last 7 Days)</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Name</th>
                                    <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Date</th>
                                    <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Q2 Supplier Performance</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-500">2026-07-20</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right">
                                        <button class="text-pink-600 hover:text-pink-900 font-medium">Download</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Mid-Month Stock Valuation</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-500">2026-07-15</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right">
                                        <button class="text-pink-600 hover:text-pink-900 font-medium">Download</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
