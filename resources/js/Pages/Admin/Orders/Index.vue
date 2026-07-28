<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import DialogModal from '@/Components/DialogModal.vue';

const showViewModal = ref(false);
const activeItem = ref(null);

const openViewModal = (item) => {
    activeItem.value = item;
    showViewModal.value = true;
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedRows.value = newSet;
};
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
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Order ID</th>
                                <th class="py-4 px-4 font-bold">Customer Name</th>
                                <th class="py-4 px-4 font-bold">Total Amount</th>
                                <th class="py-4 px-4 font-bold">Current Status</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">#1026</span>
                                    <button @click="toggleRow('1026')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('1026') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1026'), 'flex md:table-cell justify-between items-center': expandedRows.has('1026')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Customer</span>
                                    <span class="text-slate-700">Alice Reyes</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1026'), 'flex md:table-cell justify-between items-center': expandedRows.has('1026')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Amount</span>
                                    <span class="text-slate-700">₱1,200.00</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1026'), 'flex md:table-cell justify-between items-center': expandedRows.has('1026')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Pending</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('1026'), 'flex md:table-cell justify-between items-center': expandedRows.has('1026')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                    <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openViewModal('1026')">View Details</DropdownLink>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                <div class="px-4 py-2 text-xs text-slate-500">Update Status:</div>
                                                <DropdownLink as="button">Mark as Paid</DropdownLink>
                                                <DropdownLink as="button">Mark as Packing</DropdownLink>
                                                <DropdownLink as="button">Mark as Shipped</DropdownLink>
                                                <DropdownLink as="button">Mark as Delivered</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">#1025</span>
                                    <button @click="toggleRow('1025')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('1025') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1025'), 'flex md:table-cell justify-between items-center': expandedRows.has('1025')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Customer</span>
                                    <span class="text-slate-700">Hannah Sadicon</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1025'), 'flex md:table-cell justify-between items-center': expandedRows.has('1025')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Amount</span>
                                    <span class="text-slate-700">₱450.00</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('1025'), 'flex md:table-cell justify-between items-center': expandedRows.has('1025')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Paid</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('1025'), 'flex md:table-cell justify-between items-center': expandedRows.has('1025')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                    <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openViewModal('1025')">View Details</DropdownLink>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                <div class="px-4 py-2 text-xs text-slate-500">Update Status:</div>
                                                <DropdownLink as="button">Mark as Pending</DropdownLink>
                                                <DropdownLink as="button">Mark as Packing</DropdownLink>
                                                <DropdownLink as="button">Mark as Shipped</DropdownLink>
                                                <DropdownLink as="button">Mark as Delivered</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
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

        <!-- View Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Order Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <p class="mt-4 text-sm text-slate-600">Viewing details for Order <strong>#{{ activeItem }}</strong>.</p>
            </template>
            <template #footer>
                <button @click="showViewModal = false" class="btn-secondary">Close</button>
            </template>
        </DialogModal>
    </AppLayout>
</template>
