<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    transactions: Object,
    filters: Object,
    products: Array,
});

const search = ref(props.filters?.search || '');
const type = ref(props.filters?.type || '');
const product_id = ref(props.filters?.product_id || '');

watch([search, type, product_id], debounce(function ([newSearch, newType, newProduct]) {
    router.get(route('inventory-manager.stock-movement.index'), {
        search: newSearch,
        type: newType,
        product_id: newProduct,
    }, { preserveState: true, replace: true });
}, 300));

const getTypeStyles = (type) => {
    if (['add', 'receive', 'transfer_in'].includes(type)) return 'bg-green-100 text-green-700';
    if (['deduct', 'sale', 'transfer_out'].includes(type)) return 'bg-red-100 text-red-700';
    if (type === 'adjust') return 'bg-orange-100 text-orange-700';
    return 'bg-slate-100 text-slate-700';
};

const getTypeIcon = (type) => {
    if (['add', 'receive', 'transfer_in'].includes(type)) return 'M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5';
    if (['deduct', 'sale', 'transfer_out'].includes(type)) return 'M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3';
    if (type === 'adjust') return 'M12 3v18m-9-9h18';
    return '';
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

const showViewModal = ref(false);
const activeMovement = ref(null);

const openViewModal = (movement) => {
    activeMovement.value = movement;
    showViewModal.value = true;
};

const exportData = () => {
    window.location.href = route('inventory-manager.stock-movement.index', { ...props.filters, export: 'csv' });
};
</script>

<template>
    <AppLayout title="Stock Movement">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <Link :href="route('inventory-manager.dashboard')" class="hover:text-slate-900 transition-colors">Inventory Management</Link>
                        <span class="mx-2">></span>
                        <span class="font-medium text-slate-900">Stock Movement</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Stock Movement</h1>
                </div>
                <div>
                    <button @click="exportData" class="btn-secondary flex items-center gap-2 bg-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export
                        <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center flex-wrap">
                    <div class="relative w-full sm:w-auto sm:max-w-xs flex-grow">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" v-model="search" class="form-input w-full pl-10" placeholder="Search product, SKU, or reference..." />
                    </div>
                    
                    <select v-model="type" class="form-input w-full sm:w-auto">
                        <option value="">All Movement Types</option>
                        <option value="receive">Receive</option>
                        <option value="adjust">Adjustment</option>
                        <option value="sale">Sale</option>
                        <option value="transfer_in">Transfer In</option>
                        <option value="transfer_out">Transfer Out</option>
                    </select>

                    <select v-model="product_id" class="form-input w-full sm:w-auto">
                        <option value="">All Products</option>
                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>
            </div>

            <!-- Stock Movement Table -->
            <div class="glass-card overflow-hidden !p-0 bg-transparent md:bg-white shadow-none md:shadow-sm">
                <div class="p-4 border-b border-slate-100 bg-white md:bg-transparent rounded-xl md:rounded-none shadow-sm md:shadow-none mb-4 md:mb-0">
                    <h2 class="text-sm font-bold text-slate-900">Stock Movement List</h2>
                </div>
                <div class="overflow-x-auto min-w-full">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    Date & Time
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold">SKU</th>
                                <th class="py-4 px-4 font-bold">Type</th>
                                <th class="py-4 px-4 font-bold">Reference No.</th>
                                <th class="py-4 px-4 font-bold">Location</th>
                                <th class="py-4 px-4 font-bold text-center">In Qty</th>
                                <th class="py-4 px-4 font-bold text-center">Out Qty</th>
                                <th class="py-4 px-4 font-bold text-center">Balance</th>
                                <th class="py-4 px-4 font-bold">User</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="movement in transactions.data" :key="movement.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-900">{{ movement.date }}</span>
                                    </div>
                                    <button @click="toggleRow(movement.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(movement.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 flex md:table-cell justify-between items-center border-b border-slate-50 md:border-0 min-w-[220px]">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Product</span>
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col text-right md:text-left">
                                            <span class="font-bold text-slate-900">{{ movement.product_name }}</span>
                                            <span class="text-xs text-slate-400 mt-0.5">{{ movement.product_category }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">SKU</span>
                                    <span class="font-medium text-slate-700 whitespace-nowrap">{{ movement.sku }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Type</span>
                                    <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 text-xs font-bold whitespace-nowrap uppercase" :class="getTypeStyles(movement.type)">
                                        {{ movement.type.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Ref No.</span>
                                    <span class="font-medium text-slate-700">{{ movement.reference_no }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Location</span>
                                    <span class="text-slate-700 whitespace-nowrap">{{ movement.location }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">In Qty</span>
                                    <span class="font-bold text-green-600">{{ movement.in_qty || '-' }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Out Qty</span>
                                    <span class="font-bold text-red-600">{{ movement.out_qty || '-' }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Balance</span>
                                    <span class="font-bold text-slate-900">{{ movement.balance }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">User</span>
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <span class="text-sm font-medium text-slate-700">{{ movement.user }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(movement.id), 'flex md:table-cell justify-between items-center': expandedRows.has(movement.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
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
                                                <DropdownLink as="button" @click="openViewModal(movement)">
                                                    View Details
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!transactions.data || !transactions.data.length" class="block md:table-row">
                                <td colspan="11" class="py-12 text-center text-slate-500 block md:table-cell">No stock movements found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ transactions.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ transactions.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ transactions.total }}</span> results
                </div>
                <div v-if="transactions.links?.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in transactions.links" :key="k">
                            <Link v-if="link.url"
                                  :href="link.url"
                                  class="border-r border-slate-200 px-3 py-1.5 text-sm font-medium last:border-0 hover:bg-slate-50 transition-colors"
                                  :class="[link.active ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600']"
                                  v-html="link.label" />
                            <span v-else 
                                  class="border-r border-slate-200 px-3 py-1.5 text-sm font-medium last:border-0 text-slate-300 pointer-events-none" 
                                  v-html="link.label">
                            </span>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Movement Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Stock Movement Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div v-if="activeMovement" class="space-y-6 pt-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ activeMovement.reference_no || 'N/A' }}</h3>
                            <p class="text-sm text-slate-500">{{ activeMovement.date }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 text-xs font-bold whitespace-nowrap uppercase" :class="getTypeStyles(activeMovement.type)">
                            {{ activeMovement.type.replace('_', ' ') }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4">
                        <div class="col-span-2">
                            <p class="text-xs font-bold text-slate-500 uppercase">Product</p>
                            <p class="font-medium text-slate-900">{{ activeMovement.product_name }}</p>
                            <p class="text-xs text-slate-500">{{ activeMovement.sku }} | {{ activeMovement.product_category }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Location</p>
                            <p class="font-medium text-slate-900">{{ activeMovement.location }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">User</p>
                            <p class="font-medium text-slate-900">{{ activeMovement.user }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex justify-between items-center mb-2 text-green-600">
                            <span class="text-sm">In Quantity</span>
                            <span class="font-bold">+{{ activeMovement.in_qty || 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-red-600">
                            <span class="text-sm">Out Quantity</span>
                            <span class="font-bold">-{{ activeMovement.out_qty || 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                            <span class="font-bold text-slate-900">Balance After</span>
                            <span class="text-lg font-black text-slate-900">{{ activeMovement.balance }}</span>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-end w-full">
                    <button type="button" @click="showViewModal = false" class="btn-secondary">Close</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
