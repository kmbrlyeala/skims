<script setup>
import { ref, reactive } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    items: Array,
});

const showForm = ref(false);
const showDeleteModal = ref(false);
const activeItem = ref(null);

const form = useForm({
    name: '',
    description: '',
    image_url: '',
    sku: '',
    stock: 0,
    price: 0,
    status: 'draft',
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
    form.description = item.description || '';
    form.image_url = item.image_url || '';
    form.sku = item.sku;
    form.stock = item.stock;
    form.price = item.price;
    form.status = item.status;
    showForm.value = true;
};

const submit = () => {
    if (activeItem.value) {
        form.put(route('supplier.inventory.update', activeItem.value.id), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route('supplier.inventory.store'), {
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
    totalStock: props.items.reduce((sum, item) => sum + Number(item.stock || 0), 0),
    activeItems: props.items.filter((item) => item.status === 'active').length,
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
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Stock</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ inventoryStats.totalStock }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Active Listings</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ inventoryStats.activeItems }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Products</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ items.length }}</p>
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
                        <div>
                            <label class="form-label">Description</label>
                            <textarea v-model="form.description" class="form-input" rows="2" />
                            <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                        </div>
                        <div>
                            <label class="form-label">Image URL</label>
                            <input v-model="form.image_url" type="url" class="form-input" placeholder="https://..." />
                            <p v-if="form.errors.image_url" class="mt-1 text-xs text-red-600">{{ form.errors.image_url }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">SKU</label>
                                <input v-model="form.sku" class="form-input" required />
                                <p v-if="form.errors.sku" class="mt-1 text-xs text-red-600">{{ form.errors.sku }}</p>
                            </div>
                            <div>
                                <label class="form-label">Status</label>
                                <select v-model="form.status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="hidden">Hidden</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Stock</label>
                                <input v-model.number="form.stock" type="number" min="0" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Price (₱)</label>
                                <input v-model.number="form.price" type="number" min="0" step="0.01" class="form-input" />
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
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="h-9 w-9 rounded-lg object-cover" />
                                        <div v-else class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">—</div>
                                        <div>
                                            <p class="font-medium text-slate-900">{{ item.name }}</p>
                                            <p v-if="item.description" class="max-w-[200px] truncate text-xs text-slate-400">{{ item.description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-mono text-xs text-slate-500">{{ item.sku }}</td>
                                <td>
                                    <span :class="item.stock < 10 ? 'text-red-600 font-semibold' : ''">{{ item.stock }}</span>
                                </td>
                                <td class="font-semibold">₱{{ Number(item.price).toFixed(2) }}</td>
                                <td><span class="badge" :class="statusClass(item.status)">{{ item.status }}</span></td>
                                <td class="text-right">
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
                        </tbody>
                    </table>
                </div>
                <div v-if="!items.length" class="py-12 text-center text-sm text-slate-400">
                    No products yet. Click "Add Product" to get started.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
