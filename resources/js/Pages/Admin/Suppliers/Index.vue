import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showViewModal = ref(false);
const showEditModal = ref(false);
const showDisableModal = ref(false);
const showEnableModal = ref(false);
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

const openDisableModal = (item) => {
    activeItem.value = item;
    showDisableModal.value = true;
};

const openEnableModal = (item) => {
    activeItem.value = item;
    showEnableModal.value = true;
};
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
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Supplier Name</th>
                                <th class="py-4 px-4 font-bold">Contact Person</th>
                                <th class="py-4 px-4 font-bold">Email</th>
                                <th class="py-4 px-4 font-bold">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">Cosmetics Corp</span>
                                    <button @click="toggleRow('Cosmetics Corp')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('Cosmetics Corp') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Cosmetics Corp'), 'flex md:table-cell justify-between items-center': expandedRows.has('Cosmetics Corp')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Contact Person</span>
                                    <span class="text-slate-700">Jane Doe</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Cosmetics Corp'), 'flex md:table-cell justify-between items-center': expandedRows.has('Cosmetics Corp')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Email</span>
                                    <span class="text-slate-700">jane@cosmeticscorp.com</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Cosmetics Corp'), 'flex md:table-cell justify-between items-center': expandedRows.has('Cosmetics Corp')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Cosmetics Corp'), 'flex md:table-cell justify-between items-center': expandedRows.has('Cosmetics Corp')}">
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
                                                <DropdownLink as="button" @click="openViewModal('Cosmetics Corp')">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEditModal('Cosmetics Corp')">
                                                    Edit Profile
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openDisableModal('Cosmetics Corp')" class="!text-red-600 hover:!bg-red-50">
                                                    Disable
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">Skincare Logistics</span>
                                    <button @click="toggleRow('Skincare Logistics')" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has('Skincare Logistics') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Skincare Logistics'), 'flex md:table-cell justify-between items-center': expandedRows.has('Skincare Logistics')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Contact Person</span>
                                    <span class="text-slate-700">John Smith</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Skincare Logistics'), 'flex md:table-cell justify-between items-center': expandedRows.has('Skincare Logistics')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Email</span>
                                    <span class="text-slate-700">john@skincarelogistics.com</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has('Skincare Logistics'), 'flex md:table-cell justify-between items-center': expandedRows.has('Skincare Logistics')}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Disabled</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has('Skincare Logistics'), 'flex md:table-cell justify-between items-center': expandedRows.has('Skincare Logistics')}">
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
                                                <DropdownLink as="button" @click="openViewModal('Skincare Logistics')">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEditModal('Skincare Logistics')">
                                                    Edit Profile
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openEnableModal('Skincare Logistics')" class="!text-emerald-600 hover:!bg-emerald-50">
                                                    Enable
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
                    <h3 class="text-lg font-bold text-slate-900">Supplier Details</h3>
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
                    <h3 class="text-lg font-bold text-slate-900">Edit Supplier</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div>
                        <label class="form-label">Supplier Name</label>
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

        <!-- Disable Modal -->
        <DialogModal :show="showDisableModal" @close="showDisableModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Action</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to disable <span class="font-bold">{{ activeItem }}</span>?</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showDisableModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showDisableModal = false" class="btn-primary !bg-red-600 hover:!bg-red-700">Disable</button>
                </div>
            </template>
        </DialogModal>

        <!-- Enable Modal -->
        <DialogModal :show="showEnableModal" @close="showEnableModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Action</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to enable <span class="font-bold">{{ activeItem }}</span>?</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showEnableModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showEnableModal = false" class="btn-primary !bg-emerald-600 hover:!bg-emerald-700">Enable</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
