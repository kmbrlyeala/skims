<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Shipping">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Shipping Management</h1>
                    <p class="mt-1 text-sm text-slate-500">Book couriers and track shipments</p>
                </div>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                    Book Courier
                </button>
            </div>

            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Shipments</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Order #</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Courier</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Tracking No</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1001</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">J&T</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-mono">JT123456</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Shipped</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">Track</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">#1002</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">LBC</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-mono">LBC98765</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
