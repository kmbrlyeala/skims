<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Purchase Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Manage orders sent to suppliers</p>
            </div>

            <!-- Active POs -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Active Purchase Orders</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">PO Number</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Total Cost</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1042</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Acme Corp</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$1,250.00</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Sent</span></td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1043</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Global Supp</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$3,400.00</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Partially Recv'd</span></td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1044</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">TechGears</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$890.00</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Draft</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create / Manage Order -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Create / Manage Order</h2>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Link to PR</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>PR-0005: Jane Doe (Approved)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Supplier</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Acme Corp</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Order Items</label>
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Quantity</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Unit Price</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sunscreen SPF50</td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="number" value="100" class="block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$<input type="text" value="15.00" class="ml-1 inline-block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-slate-900 font-medium">$1,500.00</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Facial Cleanser</td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="number" value="50" class="block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">$<input type="text" value="8.00" class="ml-1 inline-block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-slate-900 font-medium">$400.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-right text-sm font-semibold text-slate-900">Order Total:</td>
                                <td class="px-3 py-4 text-right text-sm font-bold text-pink-600">$1,900.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Shipping Method</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Standard Delivery</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Expected Date</label>
                        <input type="date" value="2026-08-01" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50">Save as Draft</button>
                    <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">Generate & Send PO</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
