import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showViewModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const activeItem = ref(null);

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

const openViewModal = (item) => {
    activeItem.value = item;
    showViewModal.value = true;
};

const openEditModal = (item) => {
    activeItem.value = item;
    showEditModal.value = true;
};

const openDeleteModal = (item) => {
    activeItem.value = item;
    showDeleteModal.value = true;
};
<template>
    <AppLayout title="Products">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Products</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage products and supplier assignments</p>
                </div>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-500">
                    Add Product
                </button>
            </div>

            <div class="glass-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Product Name</th>
                                <th class="py-4 px-4 font-bold">Category</th>
                                <th class="py-4 px-4 font-bold">Price</th>
                                <th class="py-4 px-4 font-bold text-center">Stock</th>
                                <th class="py-4 px-4 font-bold">Supplier</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <!-- Dummy Item 1 -->
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">Moisturizer</span>
                                    <button @click="toggleRow('Moisturizer')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('Moisturizer') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Moisturizer'), 'flex md:table-cell justify-between items-center': expandedRows.has('Moisturizer')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                    <span class="text-slate-500">Skincare</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Moisturizer'), 'flex md:table-cell justify-between items-center': expandedRows.has('Moisturizer')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Price</span>
                                    <span class="text-slate-700 font-medium">₱450.00</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Moisturizer'), 'flex md:table-cell justify-between items-center': expandedRows.has('Moisturizer')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock</span>
                                    <span class="text-slate-700 font-bold">5 pcs</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Moisturizer'), 'flex md:table-cell justify-between items-center': expandedRows.has('Moisturizer')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span class="text-blue-600 font-medium">Supplier A</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Moisturizer'), 'flex md:table-cell justify-between items-center': expandedRows.has('Moisturizer')}">
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
                                                <DropdownLink as="button" @click="openViewModal('Moisturizer')">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEditModal('Moisturizer')">
                                                    Edit Product
                                                </DropdownLink>
                                                <DropdownLink as="button">
                                                    Assign Supplier
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openDeleteModal('Moisturizer')" class="!text-red-600 hover:!bg-red-50">
                                                    Delete
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Dummy Item 2 -->
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">Vitamin Serum</span>
                                    <button @click="toggleRow('Vitamin Serum')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('Vitamin Serum') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Vitamin Serum'), 'flex md:table-cell justify-between items-center': expandedRows.has('Vitamin Serum')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                    <span class="text-slate-500">Skincare</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Vitamin Serum'), 'flex md:table-cell justify-between items-center': expandedRows.has('Vitamin Serum')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Price</span>
                                    <span class="text-slate-700 font-medium">₱850.00</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Vitamin Serum'), 'flex md:table-cell justify-between items-center': expandedRows.has('Vitamin Serum')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock</span>
                                    <span class="text-slate-700 font-bold">3 pcs</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Vitamin Serum'), 'flex md:table-cell justify-between items-center': expandedRows.has('Vitamin Serum')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span class="text-slate-400 italic">None</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Vitamin Serum'), 'flex md:table-cell justify-between items-center': expandedRows.has('Vitamin Serum')}">
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
                                                <DropdownLink as="button" @click="openViewModal('Vitamin Serum')">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEditModal('Vitamin Serum')">
                                                    Edit Product
                                                </DropdownLink>
                                                <DropdownLink as="button">
                                                    Assign Supplier
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openDeleteModal('Vitamin Serum')" class="!text-red-600 hover:!bg-red-50">
                                                    Delete
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
        </div>

        <!-- View Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Product Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <p class="mt-4 text-sm text-slate-600">Viewing details for <strong>{{ activeItem }}</strong>.</p>
            </template>
            <template #footer>
                <button @click="showViewModal = false" class="btn-secondary">Close</button>
            </template>
        </DialogModal>

        <!-- Edit Modal -->
        <DialogModal :show="showEditModal" @close="showEditModal = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Edit Product</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div>
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-input" :value="activeItem" />
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex gap-3">
                    <button @click="showEditModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showEditModal = false" class="btn-primary">Save Changes</button>
                </div>
            </template>
        </DialogModal>

        <!-- Delete Modal -->
        <DialogModal :show="showDeleteModal" @close="showDeleteModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Deletion</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to delete <span class="font-bold">{{ activeItem }}</span>? This action cannot be undone.</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showDeleteModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showDeleteModal = false" class="btn-primary !bg-red-600 hover:!bg-red-700">Delete</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
