<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Products Supplied">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Products Supplied</h1>
                    <p class="mt-1 text-sm text-slate-500">Catalog of products you supply</p>
                </div>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                    Add New Product
                </button>
            </div>

            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Your Catalog</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Category</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Unit Price</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Available Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$25.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-emerald-600 font-medium">In Stock (500+)</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Toner</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$18.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-emerald-600 font-medium">In Stock (200+)</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Serum</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$35.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-amber-600 font-medium">Low Stock (50)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
