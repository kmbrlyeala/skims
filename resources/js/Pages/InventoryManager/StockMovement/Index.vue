<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Stock Movement">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Stock Movement</h1>
                <p class="mt-1 text-sm text-slate-500">Track history of all inventory changes</p>
            </div>

            <!-- Search & Filter -->
            <div class="glass-card">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700">Product</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>All Products</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700">Date</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Last 30 Days</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700">Type</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>All Types</option>
                        </select>
                    </div>
                    <button class="rounded-lg bg-pink-600 px-4 py-2 h-[38px] text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                        Search
                    </button>
                </div>
            </div>

            <!-- Movement Log -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Movement Log</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Date/Time</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Change</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Reason / Source</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">07-25 10:15</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-emerald-600">+50</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1041 Recv'd</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">07-24 14:30</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Vitamin C Serum</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-red-600">-2</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sales Ord #502</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">07-24 09:00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sunscreen SPF50</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-red-600">-1</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Damaged / Spoil</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">07-23 16:45</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Toner</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-emerald-600">+10</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Stock Adjstment</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">07-22 11:20</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Facial Cleanser</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-red-600">-5</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sales Ord #501</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manual Stock Adjustment -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Manual Stock Adjustment</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Product</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Select Product...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Type</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Addition (+)</option>
                            <option>Reduction (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Qty</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Reason</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">Record Adjustment</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
