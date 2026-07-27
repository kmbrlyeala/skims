<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Purchase Requests">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
                <p class="mt-1 text-sm text-slate-500">Create and manage requests for new stock</p>
            </div>

            <!-- Active PRs -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Active Purchase Requests</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">PR Number</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Requester</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Date</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PR-0005</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Jane Doe</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">2026-07-24</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Pending Approval</span></td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">PR-0006</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">John Smith</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">2026-07-25</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Draft</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Form -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Create New Purchase Request</h2>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Requester</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Jane Doe</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Department</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>Inventory Mgt</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Priority</label>
                        <select class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-pink-500 focus:outline-none focus:ring-pink-500 sm:text-sm">
                            <option>High</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Requested Items</label>
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Quantity</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Sunscreen SPF50</td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="number" value="100" class="block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="text" value="Low Stock" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Facial Cleanser</td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="number" value="50" class="block w-24 rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                                <td class="whitespace-nowrap px-3 py-4"><input type="text" value="Low Stock" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="mt-4 text-sm text-pink-600 font-medium hover:text-pink-500">+ Add Another Item</button>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700">Justification/Notes</label>
                    <textarea rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">Summer season approaching, need to restock sunscreen products immediately.</textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50">Save as Draft</button>
                    <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">Submit for Approval</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
