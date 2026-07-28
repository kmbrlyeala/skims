<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};
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
                    <table class="min-w-full divide-y divide-slate-200 text-sm block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Last Sold</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-center font-semibold text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0">
                            <tr class="block md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none p-4 md:p-0">
                                <td colspan="4" class="px-3 py-8 text-center text-sm text-slate-500 italic block md:table-cell">No items are currently out of stock.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="glass-card ring-1 ring-amber-500/20">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-amber-500">Low Stock Alerts (3 Items)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Stock</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Reorder Pt</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-center font-semibold text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 md:divide-y md:divide-slate-100 p-4 md:p-0">
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none">
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-medium">Sunscreen SPF50</span>
                                    <button @click="toggleRow('ls1')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('ls1') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls1'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls1')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock</span>
                                    <span class="font-bold text-red-600">3</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls1'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls1')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Reorder Pt</span>
                                    <span class="text-slate-500">10</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls1'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls1')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span class="text-slate-700">Acme Corp</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('ls1'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls1')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-center items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="() => {}">
                                                    Create PO
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none">
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-medium">Moisturizer</span>
                                    <button @click="toggleRow('ls2')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('ls2') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls2'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls2')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock</span>
                                    <span class="font-bold text-amber-600">5</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls2'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls2')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Reorder Pt</span>
                                    <span class="text-slate-500">10</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls2'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls2')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span class="text-slate-700">Global Supp</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('ls2'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls2')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-center items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="() => {}">
                                                    Create PO
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none">
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-medium">Toner</span>
                                    <button @click="toggleRow('ls3')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('ls3') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls3'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls3')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock</span>
                                    <span class="font-bold text-amber-600">8</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls3'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls3')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Reorder Pt</span>
                                    <span class="text-slate-500">15</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('ls3'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls3')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span class="text-slate-700">TechGears</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 md:py-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('ls3'), 'flex md:table-cell justify-between items-center': expandedRows.has('ls3')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-center items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="() => {}">
                                                    Create PO
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
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
