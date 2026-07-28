<script setup>
import { ref, reactive } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    suppliers: Object,
    filters: Object,
});

const showModal = ref(false);
const editingSupplier = ref(null);
const showDeactivateModal = ref(false);
const supplierToDeactivate = ref(null);

const form = useForm({
    name: '',
    contact_name: '',
    contact_email: '',
    contact_phone: '',
    source_platform: 'other',
    lead_time_days: 14,
    notes: '',
    is_active: true,
});

const openCreate = () => {
    editingSupplier.value = null;
    form.reset();
    showModal.value = true;
};

const openEdit = (supplier) => {
    editingSupplier.value = supplier;
    form.name = supplier.name;
    form.contact_name = supplier.contact_name || '';
    form.contact_email = supplier.contact_email || '';
    form.contact_phone = supplier.contact_phone || '';
    form.source_platform = supplier.source_platform;
    form.lead_time_days = supplier.lead_time_days;
    form.notes = supplier.notes || '';
    form.is_active = supplier.is_active;
    showModal.value = true;
};

const submit = () => {
    if (editingSupplier.value) {
        form.put(route('admin.suppliers.update', editingSupplier.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('admin.suppliers.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
};

const confirmDeactivate = (supplier) => {
    supplierToDeactivate.value = supplier;
    showDeactivateModal.value = true;
};

const executeDeactivate = () => {
    if (supplierToDeactivate.value) {
        router.delete(route('admin.suppliers.destroy', supplierToDeactivate.value.id), {
            onSuccess: () => {
                showDeactivateModal.value = false;
                supplierToDeactivate.value = null;
            }
        });
    }
};

const platformLabel = (p) => ({ alibaba: 'Alibaba', local_factory: 'Local Supplier', other: 'Other' }[p] || p);

const filters = reactive({ search: '', platform: '', status: '' });

const applyFilters = () => {
    router.get(route('admin.suppliers.index'), filters, { preserveState: true, replace: true });
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};
</script>

<template>
    <AppLayout title="Suppliers">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Suppliers</h2>
                <button @click="openCreate"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Supplier
                </button>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3">
                    <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search suppliers…"
                           class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    <select v-model="filters.platform" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Platforms</option>
                        <option value="alibaba">Alibaba</option>
                        <option value="local_factory">Local Supplier</option>
                        <option value="other">Other</option>
                    </select>
                    <select v-model="filters.status" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Platform</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Lead Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-gray-50 bg-white">
                            <tr v-if="suppliers.data.length === 0" class="block md:table-row">
                                <td colspan="6" class="px-6 py-12 text-center block md:table-cell">
                                    <div class="mx-auto max-w-sm space-y-3">
                                        <p class="text-sm font-medium text-slate-400">No suppliers found.</p>
                                        <div>
                                            <button
                                                @click="openCreate"
                                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105 active:scale-95"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Add Your First Supplier
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="supplier in suppliers.data" :key="supplier.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-gray-50">
                                <td class="px-6 py-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0 min-w-[200px]">
                                    <div>
                                        <Link :href="route('admin.suppliers.show', supplier.id)" class="font-medium text-gray-900 hover:text-accent">
                                            {{ supplier.name }}
                                        </Link>
                                        <p v-if="supplier.contact_email" class="text-xs text-gray-400">{{ supplier.contact_email }}</p>
                                    </div>
                                    <button @click="toggleRow(supplier.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(supplier.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 border-b border-slate-50 md:border-0" :class="{'hidden lg:table-cell': !expandedRows.has(supplier.id), 'flex lg:table-cell justify-between items-center': expandedRows.has(supplier.id)}">
                                    <span class="lg:hidden text-xs font-bold text-slate-400 uppercase">Platform</span>
                                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">
                                        {{ platformLabel(supplier.source_platform) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-b border-slate-50 md:border-0" :class="{'hidden lg:table-cell': !expandedRows.has(supplier.id), 'flex lg:table-cell justify-between items-center': expandedRows.has(supplier.id)}">
                                    <span class="lg:hidden text-xs font-bold text-slate-400 uppercase">Lead Time</span>
                                    <span class="text-sm text-gray-700">{{ supplier.lead_time_days }} days</span>
                                </td>
                                <td class="px-6 py-4 border-b border-slate-50 md:border-0 md:text-left" :class="{'hidden md:table-cell': !expandedRows.has(supplier.id), 'flex md:table-cell justify-between items-center': expandedRows.has(supplier.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Products</span>
                                    <span class="text-sm text-gray-700">{{ supplier.products_count ?? supplier.products?.length ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 border-b border-slate-50 md:border-0 md:text-left" :class="{'hidden md:table-cell': !expandedRows.has(supplier.id), 'flex md:table-cell justify-between items-center': expandedRows.has(supplier.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                                          :class="supplier.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                        {{ supplier.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(supplier.id), 'flex md:table-cell justify-between items-center': expandedRows.has(supplier.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-end items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink :href="route('admin.suppliers.show', supplier.id)">
                                                    View Supplier
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="openEdit(supplier)">
                                                    Edit Supplier
                                                </DropdownLink>
                                                <template v-if="supplier.is_active">
                                                    <div class="border-t border-slate-100 my-1"></div>
                                                    <DropdownLink as="button" @click="confirmDeactivate(supplier)" class="!text-rose-600">
                                                        Deactivate
                                                    </DropdownLink>
                                                </template>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="suppliers.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing {{ suppliers.from }} – {{ suppliers.to }} of {{ suppliers.total }}</p>
                        <div class="flex gap-2">
                            <Link v-if="suppliers.prev_page_url" :href="suppliers.prev_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</Link>
                            <Link v-if="suppliers.next_page_url" :href="suppliers.next_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-lg text-gray-900">
                        {{ editingSupplier ? 'Edit Supplier' : 'Add New Supplier' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name *</label>
                        <input v-model="form.name" type="text" required
                               class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                            <input v-model="form.contact_name" type="text"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input v-model="form.contact_phone" type="text"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                        <input v-model="form.contact_email" type="email"
                               class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source Platform *</label>
                            <select v-model="form.source_platform" required
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                                <option value="alibaba">Alibaba</option>
                                <option value="local_factory">Local Supplier</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (days) *</label>
                            <input v-model="form.lead_time_days" type="number" min="1" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes / Terms</label>
                        <textarea v-model="form.notes" rows="3"
                                  placeholder="Private label terms, packaging specs, customization notes…"
                                  class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input v-model="form.is_active" type="checkbox" id="is_active"
                               class="rounded border-gray-300 text-accent focus:ring-accent" />
                        <label for="is_active" class="text-sm text-gray-700">Active supplier</label>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="flex-1 py-2.5 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition disabled:opacity-50">
                            {{ form.processing ? 'Saving…' : (editingSupplier ? 'Update Supplier' : 'Create Supplier') }}
                        </button>
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Deactivate Confirmation Modal -->
        <Teleport to="body">
            <div
                v-if="showDeactivateModal && supplierToDeactivate"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showDeactivateModal = false"
                ></div>

                <!-- Modal Box -->
                <div
                    class="relative z-10 w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 shadow-2xl transition-all border border-slate-100/80 my-auto text-center"
                >
                    <!-- Warning Icon -->
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 mb-4 border border-rose-100">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>

                    <!-- Title & Prompt Message -->
                    <h3 class="text-lg font-bold text-slate-900">Deactivate Supplier</h3>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                        Are you sure you want to deactivate <span class="font-bold text-slate-900 font-mono bg-slate-100 px-2 py-0.5 rounded-md">{{ supplierToDeactivate.name }}</span>?
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        This supplier will be marked inactive in the system.
                    </p>

                    <!-- Actions -->
                    <div class="flex gap-3 mt-6">
                        <button
                            type="button"
                            @click="showDeactivateModal = false"
                            class="flex-1 rounded-xl border border-slate-200 bg-slate-50 py-2.5 text-xs font-bold text-slate-700 transition-all hover:bg-slate-100 active:scale-95"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="executeDeactivate"
                            class="flex-1 rounded-xl bg-rose-600 py-2.5 text-xs font-bold text-white shadow-md transition-all hover:bg-rose-700 active:scale-95"
                        >
                            Yes, Deactivate
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>
