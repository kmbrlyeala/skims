<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Products">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Products</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage products and supplier assignments</p>
                </div>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                    Add Product
                </button>
            </div>

            <div class="glass-card">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Category</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Price</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Stock</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱450.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">5 pcs</td>
                                <td class="whitespace-nowrap px-3 py-4 text-blue-600 font-medium">Supplier A</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-100">Assign Supplier</button>
                                        <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">Edit</button>
                                        <button class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Vitamin Serum</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱850.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">3 pcs</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-400 italic">None</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-100">Assign Supplier</button>
                                        <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">Edit</button>
                                        <button class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
