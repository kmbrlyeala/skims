<script setup>
import { ref, watch } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import debounce from 'lodash/debounce';
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const categoryId = ref(props.filters.category_id || 'all');
const status = ref(props.filters.status || 'all');

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

watch([search, categoryId, status], debounce(function ([newSearch, newCategoryId, newStatus]) {
    router.get(route('inventory-manager.products.index'), {
        search: newSearch,
        category_id: newCategoryId,
        status: newStatus,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const showingModal = ref(false);
const modalMode = ref('add'); // 'add', 'edit', 'delete', 'view'
const activeProduct = ref(null); // To hold the full product object for 'view' mode
const productForm = useForm({
    id: null,
    name: '',
    sku: '',
    category_id: '',
    price: 0,
    is_active: true,
});

const openAddModal = () => {
    modalMode.value = 'add';
    productForm.reset();
    showingModal.value = true;
};

const openViewModal = (product) => {
    modalMode.value = 'view';
    activeProduct.value = product;
    showingModal.value = true;
};

const openEditModal = (product) => {
    modalMode.value = 'edit';
    productForm.id = product.id;
    productForm.name = product.name;
    productForm.sku = product.sku;
    productForm.category_id = product.category_id || '';
    productForm.price = product.price;
    productForm.is_active = product.is_active;
    showingModal.value = true;
};

const openDeleteModal = (product) => {
    modalMode.value = 'delete';
    productForm.id = product.id;
    productForm.name = product.name;
    showingModal.value = true;
};

const submitProduct = () => {
    if (modalMode.value === 'add') {
        productForm.post(route('inventory-manager.products.store'), {
            onSuccess: () => showingModal.value = false
        });
    } else if (modalMode.value === 'edit') {
        productForm.put(route('inventory-manager.products.update', productForm.id), {
            onSuccess: () => showingModal.value = false
        });
    } else if (modalMode.value === 'delete') {
        productForm.delete(route('inventory-manager.products.destroy', productForm.id), {
            onSuccess: () => showingModal.value = false
        });
    }
};

const exportData = () => {
    window.location.href = route('inventory-manager.products.index', { ...props.filters, export: 'csv' });
};
</script>

<template>
    <AppLayout title="Products">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Products</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage all products in your inventory</p>
                </div>
                <div>
                    <button @click="openAddModal" class="btn-primary flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Product
                    </button>
                </div>
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
                        <input v-model="search" type="text" class="form-input w-full pl-10" placeholder="Search products by name, SKU, or barcode..." />
                    </div>
                    
                    <select v-model="categoryId" class="form-input w-full sm:w-auto min-w-[160px]">
                        <option value="all">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    
                    <select v-model="status" class="form-input w-full sm:w-auto min-w-[140px]">
                        <option value="all">All Status</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>

                    <button class="btn-secondary flex items-center gap-2 whitespace-nowrap hidden sm:flex">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        Filters
                    </button>
                </div>
                
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <button @click="exportData" class="btn-secondary flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export
                    </button>
                    <button class="btn-secondary flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>

            <!-- Product Table -->
            <div class="glass-card overflow-hidden !p-0 bg-transparent md:bg-white shadow-none md:shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold">SKU</th>
                                <th class="py-4 px-4 font-bold">Category</th>
                                <th class="py-4 px-4 font-bold text-center">Current Stock</th>
                                <th class="py-4 px-4 font-bold text-center">Min. Stock</th>
                                <th class="py-4 px-4 font-bold text-right">Price</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="product in products.data" :key="product.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 shrink-0 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                                            <img v-if="product.photo_urls?.length" :src="product.photo_urls[0]" class="h-full w-full object-cover" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ product.name }}</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Barcode: {{ product.sku.replace('-', '') }}845</span>
                                        </div>
                                    </div>
                                    <button @click="toggleRow(product.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(product.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">SKU</span>
                                    <span class="font-medium text-slate-700 whitespace-nowrap">{{ product.sku }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                    <span class="whitespace-nowrap">{{ product.category?.name || 'Uncategorized' }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Current Stock</span>
                                    <span class="font-bold text-slate-900" :class="{'text-red-600': (product.inventory?.on_hand_qty || 0) === 0}">
                                        {{ product.inventory?.on_hand_qty || 0 }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Min. Stock</span>
                                    <span class="text-slate-500">
                                        {{ product.inventory?.reorder_point || 0 }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Price</span>
                                    <span class="font-medium text-slate-900 whitespace-nowrap">
                                        ₱{{ Number(product.price).toFixed(2) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span v-if="(product.inventory?.on_hand_qty || 0) > (product.inventory?.reorder_point || 0) && (product.inventory?.on_hand_qty || 0) > 0" class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-green-100 text-green-700">In Stock</span>
                                    <span v-else-if="(product.inventory?.on_hand_qty || 0) > 0" class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-orange-100 text-orange-700">Low Stock</span>
                                    <span v-else class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700">Out of Stock</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(product.id), 'flex md:table-cell justify-between items-center': expandedRows.has(product.id)}">
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
                                                <DropdownLink as="button" @click="openViewModal(product)">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEditModal(product)">
                                                    Edit
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openDeleteModal(product)" class="!text-red-600 hover:!bg-red-50">
                                                    Delete
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!products.data.length" class="block md:table-row">
                                <td colspan="8" class="py-12 text-center text-slate-500 block md:table-cell">No products found matching your filters.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ products.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ products.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ products.total }}</span> results
                </div>
                <div v-if="products.links.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in products.links" :key="k">
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

        <!-- Product Modal (Add/Edit/Delete/View) -->
        <DialogModal :show="showingModal" @close="showingModal = false">
            <template #title>
                {{ modalMode === 'add' ? 'Add New Product' : (modalMode === 'edit' ? 'Edit Product' : (modalMode === 'delete' ? 'Delete Product' : 'Product Details')) }}
            </template>

            <template #content>
                <div v-if="modalMode === 'delete'">
                    Are you sure you want to delete <strong>{{ productForm.name }}</strong>? This action may not be reversible.
                </div>
                <div v-else-if="modalMode === 'view' && activeProduct" class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="h-16 w-16 shrink-0 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                            <img v-if="activeProduct.photo_urls?.length" :src="activeProduct.photo_urls[0]" class="h-full w-full object-cover" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ activeProduct.name }}</h3>
                            <p class="text-sm text-slate-500">SKU: {{ activeProduct.sku }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Category</p>
                            <p class="font-medium text-slate-900">{{ activeProduct.category?.name || 'Uncategorized' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Price</p>
                            <p class="font-medium text-slate-900">₱{{ Number(activeProduct.price).toFixed(2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Current Stock</p>
                            <p class="font-medium text-slate-900">{{ activeProduct.inventory?.on_hand_qty || 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Min. Stock</p>
                            <p class="font-medium text-slate-900">{{ activeProduct.inventory?.reorder_point || 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Status</p>
                            <span v-if="(activeProduct.inventory?.on_hand_qty || 0) > (activeProduct.inventory?.reorder_point || 0) && (activeProduct.inventory?.on_hand_qty || 0) > 0" class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-green-100 text-green-700 mt-1">In Stock</span>
                            <span v-else-if="(activeProduct.inventory?.on_hand_qty || 0) > 0" class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-orange-100 text-orange-700 mt-1">Low Stock</span>
                            <span v-else class="inline-flex rounded px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 mt-1">Out of Stock</span>
                        </div>
                    </div>
                </div>
                <div v-else class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Product Name" />
                        <TextInput id="name" v-model="productForm.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="productForm.errors.name" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="sku" value="SKU / Barcode" />
                            <TextInput id="sku" v-model="productForm.sku" type="text" class="mt-1 block w-full" required />
                            <InputError :message="productForm.errors.sku" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="price" value="Price (₱)" />
                            <TextInput id="price" v-model="productForm.price" type="number" step="0.01" class="mt-1 block w-full" required />
                            <InputError :message="productForm.errors.price" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="category" value="Category" />
                        <select id="category" v-model="productForm.category_id" class="form-input mt-1 block w-full" required>
                            <option value="" disabled>Select a Category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <InputError :message="productForm.errors.category_id" class="mt-2" />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton v-if="modalMode === 'view'" @click="showingModal = false">
                    Close
                </SecondaryButton>
                <SecondaryButton v-else @click="showingModal = false">
                    Cancel
                </SecondaryButton>

                <DangerButton v-if="modalMode === 'delete'" class="ml-3" :class="{ 'opacity-25': productForm.processing }" :disabled="productForm.processing" @click="submitProduct">
                    Delete Product
                </DangerButton>
                
                <PrimaryButton v-if="modalMode === 'add' || modalMode === 'edit'" class="ml-3" :class="{ 'opacity-25': productForm.processing }" :disabled="productForm.processing" @click="submitProduct">
                    {{ modalMode === 'add' ? 'Save Product' : 'Update Product' }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
