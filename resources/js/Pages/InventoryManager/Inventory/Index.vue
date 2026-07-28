<script setup>
import { reactive, ref, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    inventory: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const categoryId = ref(props.filters?.category_id || 'all');
const stockStatus = ref(props.filters?.stock || '');

watch([search, categoryId, stockStatus], debounce(function ([newSearch, newCategoryId, newStockStatus]) {
    router.get(route('inventory-manager.supply-inventory.index'), {
        search: newSearch,
        category_id: newCategoryId,
        stock: newStockStatus,
    }, { preserveState: true, replace: true });
}, 300));

// Manage Stock Modal (Strictly for Adjustments)
const isAdjustModalOpen = ref(false);
const isViewModalOpen = ref(false);
const activeInventoryItem = ref(null);

const openViewModal = (inv) => {
    activeInventoryItem.value = inv;
    isViewModalOpen.value = true;
};

const adjustForm = useForm({
    type: 'adjust',
    product_id: '',
    quantity: '',
    notes: '',
});

const openAdjustModal = (inv = null) => {
    adjustForm.reset();
    if (inv) {
        adjustForm.product_id = inv.product_id;
        adjustForm.quantity = inv.on_hand_qty;
    }
    isAdjustModalOpen.value = true;
};

const submitAdjustTransaction = () => {
    if (!adjustForm.product_id) return;
    adjustForm.post(route('inventory-manager.supply-inventory.transaction', adjustForm.product_id), {
        preserveScroll: true,
        onSuccess: () => {
            isAdjustModalOpen.value = false;
        },
    });
};

const getStatusStyles = (item) => {
    if (item.is_out_of_stock) return 'bg-red-100 text-red-700';
    if (item.is_low_stock)    return 'bg-orange-100 text-orange-700';
    return 'bg-green-100 text-green-700';
};
const getStatusLabel = (item) => {
    if (item.is_out_of_stock) return 'Out of Stock';
    if (item.is_low_stock)    return 'Low Stock';
    return 'In Stock';
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

const exportData = () => {
    window.location.href = route('inventory-manager.supply-inventory.index', { ...props.filters, export: 'csv' });
};
</script>

<template>
    <AppLayout title="Inventory">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <Link :href="route('inventory-manager.dashboard')" class="hover:text-slate-900 transition-colors">Inventory Management</Link>
                        <span class="mx-2">></span>
                        <span class="font-medium text-slate-900">Inventory</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Inventory</h1>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('inventory-manager.stock-movement.index')" class="btn-secondary flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Stock History
                    </Link>
                    <button @click="exportData" class="btn-secondary flex items-center gap-2 bg-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="stockStatus = ''" :class="[stockStatus === '' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-colors']">
                        All Stock
                    </button>
                    <button @click="stockStatus = 'ok'" :class="[stockStatus === 'ok' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-colors']">
                        In Stock
                    </button>
                    <button @click="stockStatus = 'low'" :class="[stockStatus === 'low' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-colors']">
                        Low Stock
                    </button>
                    <button @click="stockStatus = 'out'" :class="[stockStatus === 'out' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-colors']">
                        Out of Stock
                    </button>
                </nav>
            </div>

            <!-- Search & Filter Bar -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full max-w-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input v-model="search" type="text" class="form-input w-full pl-10" placeholder="Search product by name, SKU, or barcode..." />
                    </div>
                    
                    <select v-model="categoryId" class="form-input w-full sm:w-auto min-w-[160px]">
                        <option value="all">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <button class="btn-secondary flex items-center gap-2 whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        Filters
                    </button>
                </div>
                
                <div class="flex items-center gap-3">
                    <button @click="openAdjustModal()" class="btn-secondary flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688 0-1.37-.256-1.893-.769l-3.666-3.665a2.671 2.671 0 0 1 3.778-3.778l1.782 1.782 4.14-4.14a2.67 2.67 0 1 1 3.776 3.777L12.233 15.07c-.524.513-1.205.769-1.893.769Z" />
                        </svg>
                        Adjust Stock
                    </button>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="glass-card overflow-hidden !p-0 bg-transparent md:bg-white shadow-none md:shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold">SKU</th>
                                <th class="py-4 px-4 font-bold">Category</th>
                                <th class="py-4 px-4 font-bold text-center">Current Stock</th>
                                <th class="py-4 px-4 font-bold text-center">Reserved</th>
                                <th class="py-4 px-4 font-bold text-center">Available Stock</th>
                                <th class="py-4 px-4 font-bold text-center">Batch / Expiration</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="inv in inventory.data" :key="inv.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0 min-w-[250px]">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 shrink-0 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                                            <img v-if="inv.photo_urls?.length" :src="inv.photo_urls[0]" class="h-full w-full object-cover" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ inv.product_name }}</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Barcode: {{ inv.sku.replace('-', '') }}845</span>
                                        </div>
                                    </div>
                                    <button @click="toggleRow(inv.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(inv.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">SKU</span>
                                    <span class="font-medium text-slate-700 whitespace-nowrap">{{ inv.sku }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                    <span class="whitespace-nowrap">{{ inv.category_name }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Current Stock</span>
                                    <span class="font-bold text-slate-900" :class="{'text-red-600': inv.on_hand_qty === 0}">
                                        {{ inv.on_hand_qty }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Reserved</span>
                                    <span class="font-medium text-slate-500">
                                        {{ inv.reserved_qty }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Available</span>
                                    <span class="font-bold text-indigo-600" :class="{'text-red-600': inv.available_qty === 0}">
                                        {{ inv.available_qty }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Batch/Exp</span>
                                    <div class="flex flex-col items-end md:items-center">
                                        <span class="font-medium text-slate-700">{{ inv.batch_number }}</span>
                                        <span class="text-xs text-slate-400 mt-0.5">{{ inv.expiration_date }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex rounded px-2.5 py-1 text-xs font-bold whitespace-nowrap" :class="getStatusStyles(inv)">
                                        {{ getStatusLabel(inv) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(inv.id), 'flex md:table-cell justify-between items-center': expandedRows.has(inv.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-center items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openViewModal(inv)">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openAdjustModal(inv)">
                                                    Adjust Stock
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!inventory.data.length" class="block md:table-row">
                                <td colspan="9" class="py-12 text-center text-slate-500 block md:table-cell">No inventory records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ inventory.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ inventory.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ inventory.total }}</span> results
                </div>
                <div v-if="inventory.links?.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in inventory.links" :key="k">
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

        <!-- Adjust Stock Modal -->
        <DialogModal :show="isAdjustModalOpen" @close="isAdjustModalOpen = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Adjust Stock</h3>
                    <button @click="isAdjustModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-100 text-amber-800 text-sm mb-4">
                    <strong>Note:</strong> This action is for manual corrections (e.g., damaged goods, count errors). Regular stock changes should happen via Delivery Receipts or Sales Orders.
                </div>
                <form @submit.prevent="submitAdjustTransaction" class="space-y-4 pt-1">
                    <div>
                        <label class="form-label text-sm text-slate-700">Product</label>
                        <select v-model="adjustForm.product_id" required class="form-select mt-1 w-full">
                            <option value="" disabled>Select Product to Adjust</option>
                            <option v-for="inv in inventory.data" :key="inv.id" :value="inv.product_id">{{ inv.product_name }} (Current: {{ inv.on_hand_qty }})</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label text-sm text-slate-700">New Corrected Quantity (Absolute)</label>
                        <input v-model.number="adjustForm.quantity" type="number" min="0" required class="form-input mt-1 w-full" placeholder="e.g. 10" />
                        <p class="text-xs text-slate-500 mt-1">Set to the exact physical quantity currently on hand.</p>
                    </div>
                    
                    <div>
                        <label class="form-label text-sm text-slate-700">Reason for Adjustment</label>
                        <textarea v-model="adjustForm.notes" rows="2" required class="form-input mt-1 w-full" placeholder="e.g. Damaged good discovered during audit"></textarea>
                    </div>
                </form>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="isAdjustModalOpen = false" class="btn-secondary">Cancel</button>
                    <button type="button" @click="submitAdjustTransaction" :disabled="adjustForm.processing || !adjustForm.notes.trim()" class="btn-primary">
                        {{ adjustForm.processing ? 'Saving...' : 'Submit Adjustment' }}
                    </button>
                </div>
            </template>
        </DialogModal>

        <!-- View Details Modal -->
        <DialogModal :show="isViewModalOpen" @close="isViewModalOpen = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Inventory Details</h3>
                    <button @click="isViewModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div v-if="activeInventoryItem" class="space-y-6 pt-1">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="h-16 w-16 shrink-0 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                            <img v-if="activeInventoryItem.photo_urls?.length" :src="activeInventoryItem.photo_urls[0]" class="h-full w-full object-cover" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ activeInventoryItem.product_name }}</h3>
                            <p class="text-sm text-slate-500">SKU: {{ activeInventoryItem.sku }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Category</p>
                            <p class="font-medium text-slate-900">{{ activeInventoryItem.category_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Status</p>
                            <span class="inline-flex rounded px-2.5 py-1 text-xs font-bold mt-1" :class="getStatusStyles(activeInventoryItem)">
                                {{ getStatusLabel(activeInventoryItem) }}
                            </span>
                        </div>
                        
                        <div class="col-span-2 border-t border-slate-100 my-2 pt-4">
                            <h4 class="text-sm font-bold text-slate-900 mb-3">Stock Levels</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Available</p>
                                    <p class="text-xl font-bold text-indigo-600">{{ activeInventoryItem.available_qty }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Reserved</p>
                                    <p class="text-xl font-bold text-slate-700">{{ activeInventoryItem.reserved_qty }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">On Hand</p>
                                    <p class="text-xl font-bold text-slate-900">{{ activeInventoryItem.on_hand_qty }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Reorder Point</p>
                            <p class="font-medium text-slate-900">{{ activeInventoryItem.reorder_point || 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Batch / Expiration</p>
                            <p class="font-medium text-slate-900">{{ activeInventoryItem.batch_number || 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ activeInventoryItem.expiration_date || 'No exp. date' }}</p>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-end w-full">
                    <button type="button" @click="isViewModalOpen = false" class="btn-secondary">Close</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
