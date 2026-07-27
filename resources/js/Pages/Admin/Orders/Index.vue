<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Order Management</h1>
                <p class="mt-1 text-sm text-slate-500">Manage customer orders and update their statuses</p>
            </div>

            <div class="glass-card">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Order ID</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Customer Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Total Amount</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Current Status</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1026</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Alice Reyes</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱1,200.00</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Pending</span></td>
                                <td class="whitespace-nowrap px-3 py-4">
                                    <select class="block w-full max-w-[150px] rounded-md border-slate-300 py-1.5 pl-3 pr-10 text-xs focus:border-pink-500 focus:outline-none focus:ring-pink-500">
                                        <option selected>Pending</option>
                                        <option>Paid</option>
                                        <option>Packing</option>
                                        <option>Shipped</option>
                                        <option>Delivered</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Hannah Sadicon</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱450.00</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Paid</span></td>
                                <td class="whitespace-nowrap px-3 py-4">
                                    <select class="block w-full max-w-[150px] rounded-md border-slate-300 py-1.5 pl-3 pr-10 text-xs focus:border-pink-500 focus:outline-none focus:ring-pink-500">
                                        <option>Pending</option>
                                        <option selected>Paid</option>
                                        <option>Packing</option>
                                        <option>Shipped</option>
                                        <option>Delivered</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="glass-card mt-8 ring-1 ring-slate-200">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Order Status Flow:</h3>
                <div class="bg-slate-900 rounded-lg p-6 max-w-sm text-slate-300 font-mono text-sm space-y-4">
                    <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-slate-600"></span> Pending</div>
                    <div class="ml-1 text-slate-600">↓</div>
                    <div class="flex items-center gap-3 text-white"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Paid</div>
                    <div class="ml-1 text-slate-600">↓</div>
                    <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-slate-600"></span> Packing</div>
                    <div class="ml-1 text-slate-600">↓</div>
                    <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-slate-600"></span> Shipped</div>
                    <div class="ml-1 text-slate-600">↓</div>
                    <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-slate-600"></span> Delivered</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
