<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Delivery History">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Delivery History</h1>
                <p class="mt-1 text-sm text-slate-500">Record of all completed shipments</p>
            </div>

            <div class="glass-card">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700">Date Range</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Last 30 Days</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <button class="rounded-lg bg-pink-600 px-4 py-2 h-[38px] text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                        Filter
                    </button>
                </div>
            </div>

            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Past Deliveries</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Tracking No</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">PO Number</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Date Delivered</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">TRK-9800X</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1030</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">2026-07-20</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">TRK-9755Y</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PO-1025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">2026-07-15</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Delivered</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
