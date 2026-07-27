<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Suppliers">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Supplier Management</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage vendor accounts and profiles</p>
                </div>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                    Add Supplier
                </button>
            </div>

            <div class="glass-card">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Contact Person</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Email</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Cosmetics Corp</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Jane Doe</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">jane@cosmeticscorp.com</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">View</button>
                                        <button class="rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-100">Edit</button>
                                        <button class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Disable</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Skincare Logistics</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">John Smith</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">john@skincarelogistics.com</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Disabled</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-200">View</button>
                                        <button class="rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-100">Edit</button>
                                        <button class="rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-100">Enable</button>
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
