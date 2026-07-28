import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showViewModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const activeItem = ref(null);

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
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product Name</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Category</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Price</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Stock</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Supplier</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱450.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">5 pcs</td>
                                <td class="whitespace-nowrap px-3 py-4 text-blue-600 font-medium">Supplier A</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">Vitamin Serum</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Skincare</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">₱850.00</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">3 pcs</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-400 italic">None</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
