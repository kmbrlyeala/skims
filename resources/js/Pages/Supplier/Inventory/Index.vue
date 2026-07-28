<script setup>
import { ref, reactive } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    products: Array,
});

const showForm = ref(false);
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

const form = useForm({
    name: '',
    sku: '',
    unit_cost: 0,
    moq: 1,
});

const resetForm = () => {
    form.reset();
    activeItem.value = null;
    showForm.value = false;
};

const openCreate = () => {
    form.reset();
    activeItem.value = null;
    showForm.value = true;
};

const openEdit = (item) => {
    activeItem.value = item;
    form.name = item.name;
    form.sku = item.sku;
    form.unit_cost = item.unit_cost;
    form.moq = item.moq;
    showForm.value = true;
};

const submit = () => {
    if (activeItem.value) {
        form.put(route('supplier.inventory.update', activeItem.value.id), {
            preserveScroll: true,
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route('supplier.inventory.store'), {
            preserveScroll: true,
            onSuccess: () => resetForm(),
        });
    }
};

const openDelete = (item) => {
    activeItem.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    if (activeItem.value) {
        router.delete(route('supplier.inventory.destroy', activeItem.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                activeItem.value = null;
            }
        });
    }
};

const statusClass = (status) => ({
    active: 'badge-active',
    draft: 'badge-draft',
    hidden: 'badge-hidden',
}[status] || 'badge-draft');

const inventoryStats = reactive({
    totalProducts: props.products ? props.products.length : 0,
});
</script>

<template>
    <AppLayout title="Inventory">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Inventory</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage your product catalog</p>
                </div>
                <button @click="openCreate" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Products</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ inventoryStats.totalProducts }}</p>
                </div>
            </div>

            <!-- Form Modal -->
            <DialogModal :show="showForm" @close="resetForm" maxWidth="lg">
                <template #title>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ activeItem ? 'Edit Product' : 'Add New Product' }}
                        </h3>
                        <button @click="resetForm" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </template>
                <template #content>
                    <form @submit.prevent="submit" class="mt-2 space-y-4">
                        <div>
                            <label class="form-label">Name</label>
                            <input v-model="form.name" class="form-input" required />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">SKU</label>
                                <input v-model="form.sku" class="form-input" required :disabled="activeItem" />
                                <p v-if="form.errors.sku" class="mt-1 text-xs text-red-600">{{ form.errors.sku }}</p>
                            </div>
                            <div>
                                <label class="form-label">Minimum Order Qty (MOQ)</label>
                                <input v-model.number="form.moq" type="number" min="1" class="form-input" />
                                <p v-if="form.errors.moq" class="mt-1 text-xs text-red-600">{{ form.errors.moq }}</p>
                            </div>
                            <div>
                                <label class="form-label">Unit Cost (₱)</label>
                                <input v-model.number="form.unit_cost" type="number" min="0" step="0.01" class="form-input" />
                                <p v-if="form.errors.unit_cost" class="mt-1 text-xs text-red-600">{{ form.errors.unit_cost }}</p>
                            </div>
                        </div>
                    </form>
                </template>
                <template #footer>
                    <div class="flex items-center justify-end gap-3 w-full">
                        <button type="button" @click="resetForm" class="btn-secondary">Cancel</button>
                        <button type="button" @click="submit" :disabled="form.processing" class="btn-primary">
                            {{ activeItem ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </template>
            </DialogModal>

            <!-- Delete Confirmation Modal -->
            <DialogModal :show="showDeleteModal" @close="showDeleteModal = false" maxWidth="sm">
                <template #title>
                    <h3 class="text-lg font-bold text-slate-900">Confirm Deletion</h3>
                </template>
                <template #content>
                    <p class="text-sm text-slate-600">Are you sure you want to delete <span class="font-bold">{{ activeItem?.name }}</span>? This action cannot be undone.</p>
                </template>
                <template #footer>
                    <div class="flex items-center justify-end gap-3 w-full">
                        <button type="button" @click="showDeleteModal = false" class="btn-secondary">Cancel</button>
                        <button type="button" @click="deleteItem" class="btn-primary !bg-red-600 hover:!bg-red-700">Delete</button>
                    </div>
                </template>
            </DialogModal>

            <!-- Products Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold">SKU</th>
                                <th class="py-4 px-4 font-bold">Category</th>
                                <th class="py-4 px-4 font-bold text-center">MOQ</th>
                                <th class="py-4 px-4 font-bold text-right">Unit Cost</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="item in products" :key="item.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">—</div>
                                        <div>
                                            <p class="font-medium text-slate-900">{{ item.name }}</p>
                                        </div>
                                    </div>
                                    <button @click="toggleRow(item.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(item.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">SKU</span>
                                    <span class="font-mono text-xs text-slate-500">{{ item.sku }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                    <span class="text-slate-600">{{ item.category }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">MOQ</span>
                                    <span class="text-slate-600">{{ item.moq }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Unit Cost</span>
                                    <span class="font-semibold">₱{{ Number(item.unit_cost).toFixed(2) }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
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
                                                <DropdownLink as="button" @click="openEdit(item)">
                                                    Edit Product
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openDelete(item)" class="!text-red-600 hover:!bg-red-50">
                                                    Delete
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!products.length" class="block md:table-row">
                                <td colspan="6" class="py-12 text-center text-sm text-slate-400 block md:table-cell">
                                    No products yet. Click "Add Product" to get started.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
