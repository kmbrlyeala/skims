<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Low Stock">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Low Stock</h1>
                <p class="mt-1 text-sm text-slate-500">Products below their specified reorder points</p>
            </div>

            <!-- Urgent: Out of Stock -->
            <div class="glass-card ring-1 ring-red-500/20">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-red-500">Urgent: Out of Stock (0 Items)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Last Sold</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="px-3 py-8 text-center text-sm text-slate-500 italic">No items are currently out of stock.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="glass-card ring-1 ring-amber-500/20">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-amber-500">Low Stock Alerts (3 Items)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Stock</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Reorder Pt</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sunscreen SPF50</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-red-600">3</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">10</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Acme Corp</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <button class="rounded-lg bg-pink-100 px-3 py-1.5 text-xs font-medium text-pink-700 hover:bg-pink-200">Create PO</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-amber-600">5</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">10</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Global Supp</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <button class="rounded-lg bg-pink-100 px-3 py-1.5 text-xs font-medium text-pink-700 hover:bg-pink-200">Create PO</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Toner</td>
                                <td class="whitespace-nowrap px-3 py-4 font-bold text-amber-600">8</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">15</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">TechGears</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <button class="rounded-lg bg-pink-100 px-3 py-1.5 text-xs font-medium text-pink-700 hover:bg-pink-200">Create PO</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Auto-Reorder Settings -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Auto-Reorder Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="auto-reorder" type="checkbox" checked class="h-4 w-4 rounded border-slate-300 text-pink-600 focus:ring-pink-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="auto-reorder" class="font-medium text-slate-700">Enable automatic Purchase Request generation</label>
                            <p class="text-slate-500">Automatically draft a PR when items fall below their reorder point.</p>
                        </div>
                    </div>

                    <div class="pt-4 max-w-xs">
                        <label class="block text-sm font-medium text-slate-700">Default PR Priority</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Normal</option>
                            <option>High</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50">Save Settings</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
