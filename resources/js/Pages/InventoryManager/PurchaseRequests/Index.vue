<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    purchaseRequests: Object,
    filters: Object,
    isManager: Boolean,
    routePrefix: {
        type: String,
        default: 'inventory-manager',
    },
    products: Array,
    suppliers: Array,
    prefill: Object,
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
const supplier_id = ref('');

watch([search, status], debounce(function ([newSearch, newStatus]) {
    router.get(route(`${props.routePrefix}.purchase-requests.index`), {
        search: newSearch,
        status: newStatus,
    }, { preserveState: true, replace: true });
}, 300));

// Modal Form Logic
const showCreateModal = ref(false);
const showViewModal = ref(false);
const activePR = ref(null);

const openViewModal = (pr) => {
    activePR.value = pr;
    showViewModal.value = true;
};

const form = useForm({
    supplier_id: props.prefill?.supplier_id ?? '',
    product_id: props.prefill?.product_id ?? '',
    quantity_requested: props.prefill?.quantity ?? '',
    unit_cost: props.prefill?.unit_cost ?? '',
    expected_delivery_date: '',
    notes: '',
});

const selectedSupplier = computed(() => props.suppliers?.find(s => s.id == form.supplier_id));

const availableProducts = computed(() => {
    if (!selectedSupplier.value || !props.products) return [];
    return props.products.filter(p => p.suppliers.some(s => s.id == form.supplier_id));
});

const selectedSupplierData = computed(() => {
    if (!form.product_id || !form.supplier_id || !props.products) return null;
    const product = props.products.find(p => p.id == form.product_id);
    return product?.suppliers?.find(s => s.id == form.supplier_id) ?? null;
});

watch(selectedSupplierData, (sd) => {
    if (sd && !props.prefill?.unit_cost) {
        form.unit_cost = sd.unit_cost;
    }
});

watch(() => form.supplier_id, (newSupplierId) => {
    if (newSupplierId) {
        const productStillValid = availableProducts.value.some(p => p.id == form.product_id);
        if (!productStillValid) {
            form.product_id = '';
            form.unit_cost = '';
        }
    }
});

const submit = () => {
    form.post(route(`${props.routePrefix}.purchase-requests.store`), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const getStatusBadge = (status) => {
    if (status.includes('pending') || status === 'draft') return 'bg-orange-100 text-orange-700';
    if (status.includes('approved')) return 'bg-blue-100 text-blue-700';
    if (status.includes('received')) return 'bg-green-100 text-green-700';
    if (status === 'ordered') return 'bg-purple-100 text-purple-700';
    if (status === 'rejected') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-700';
};

const formatPRNumber = (pr) => {
    const year = new Date(pr.created_at).getFullYear();
    return `PR-${year}-${String(pr.id).padStart(4, '0')}`;
};

const canEdit = (status) => {
    return status === 'draft' || status.includes('pending') || status === 'approved';
};

const expandedActive = ref(new Set());
const toggleActive = (id) => {
    const newSet = new Set(expandedActive.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedActive.value = newSet;
};
</script>

<template>
    <AppLayout title="Purchase Requests">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <Link :href="route('inventory-manager.dashboard')" class="hover:text-slate-900 transition-colors">Inventory Management</Link>
                        <span class="mx-2">></span>
                        <span class="font-medium text-slate-900">Purchase Requests</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
                    <p class="mt-1 text-sm text-slate-500">Create and manage requests for new stock</p>
                </div>
                <div>
                    <button @click="showCreateModal = true" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Purchase Request
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
                        <input type="text" v-model="search" class="form-input w-full pl-10" placeholder="Search request no., supplier, or requested by..." />
                    </div>
                    
                    <select v-model="status" class="form-input w-full sm:w-auto min-w-[150px]">
                        <option value="">All Status</option>
                        <option value="pending_approval">Pending Approval</option>
                        <option value="approved">Approved</option>
                        <option value="ordered">Ordered</option>
                        <option value="received">Received</option>
                    </select>

                    <button class="btn-secondary flex items-center gap-2 whitespace-nowrap hidden sm:flex">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        Filters
                    </button>
                </div>
            </div>

            <!-- Active PRs -->
            <div class="glass-card !p-0 overflow-hidden bg-transparent md:bg-white shadow-none md:shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold text-left">PR Number</th>
                                <th class="py-4 px-4 font-bold text-left">Requester</th>
                                <th class="py-4 px-4 font-bold text-left">Supplier</th>
                                <th class="py-4 px-4 font-bold text-left">Date Requested</th>
                                <th class="py-4 px-4 font-bold text-center">Items</th>
                                <th class="py-4 px-4 font-bold text-right">Est. Total (₱)</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="pr in purchaseRequests.data" :key="pr.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold">{{ formatPRNumber(pr) }}</span>
                                    <button @click="toggleActive(pr.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedActive.has(pr.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Requester</span>
                                    <span>{{ pr.requester }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span>{{ pr.supplier.name }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Date</span>
                                    <span>{{ pr.created_at }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Items</span>
                                    <span class="font-bold text-slate-700">{{ pr.quantity_requested }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Est. Total</span>
                                    <span class="font-bold text-slate-900">₱{{ Number(pr.total_cost).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold" :class="getStatusBadge(pr.status)">{{ pr.status_label }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(pr.id), 'flex md:table-cell justify-between items-center': expandedActive.has(pr.id)}">
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
                                                <DropdownLink as="button" @click="openViewModal(pr)">
                                                    View Details
                                                </DropdownLink>
                                                <template v-if="isManager && pr.status === 'pending_approval'">
                                                    <div class="border-t border-slate-100"></div>
                                                    <DropdownLink :href="route(`${routePrefix}.purchase-requests.approve`, pr.id)" method="patch" as="button" class="!text-green-600 hover:!bg-green-50">
                                                        Approve
                                                    </DropdownLink>
                                                    <DropdownLink :href="route(`${routePrefix}.purchase-requests.reject`, pr.id)" method="patch" as="button" class="!text-red-600 hover:!bg-red-50">
                                                        Reject
                                                    </DropdownLink>
                                                </template>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!purchaseRequests.data.length" class="block md:table-row">
                                <td colspan="8" class="py-12 text-center text-slate-500 block md:table-cell">No purchase requests found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ purchaseRequests.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ purchaseRequests.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ purchaseRequests.total }}</span> results
                </div>
                <div v-if="purchaseRequests.links?.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in purchaseRequests.links" :key="k">
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

        <!-- Create PR Modal -->
        <DialogModal :show="showCreateModal" @close="showCreateModal = false" maxWidth="2xl">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">New Purchase Request</h3>
                    <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <form @submit.prevent="submit" class="space-y-6 pt-2">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label text-sm font-bold text-slate-700">Supplier <span class="text-red-500">*</span></label>
                            <select v-model="form.supplier_id" required class="form-select w-full mt-1">
                                <option value="" disabled>Select a supplier…</option>
                                <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-sm font-bold text-slate-700">Product / SKU <span class="text-red-500">*</span></label>
                            <select v-model="form.product_id" required class="form-select w-full mt-1" :disabled="!form.supplier_id">
                                <option value="" disabled>{{ form.supplier_id ? 'Select a product…' : 'Select a supplier first' }}</option>
                                <option v-for="p in availableProducts" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-sm font-bold text-slate-700">Quantity <span class="text-red-500">*</span></label>
                                <input v-model="form.quantity_requested" type="number" min="1" required class="form-input w-full mt-1" />
                            </div>
                            <div>
                                <label class="form-label text-sm font-bold text-slate-700">Need By Date</label>
                                <input v-model="form.expected_delivery_date" type="date" class="form-input w-full mt-1" />
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-sm font-bold text-slate-700">Justification / Notes</label>
                            <textarea v-model="form.notes" rows="2" placeholder="Any special instructions or reasons…" class="form-input w-full mt-1"></textarea>
                        </div>
                    </div>
                </form>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="showCreateModal = false" class="btn-secondary">Cancel</button>
                    <button type="button" @click="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Submitting…' : 'Submit PR' }}
                    </button>
                </div>
            </template>
        </DialogModal>

        <!-- View PR Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="lg">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Purchase Request Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div v-if="activePR" class="space-y-6 pt-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ formatPRNumber(activePR) }}</h3>
                            <p class="text-sm text-slate-500">Requested on {{ activePR.created_at }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-bold" :class="getStatusBadge(activePR.status)">{{ activePR.status_label }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Supplier</p>
                            <p class="font-medium text-slate-900">{{ activePR.supplier?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Requester</p>
                            <p class="font-medium text-slate-900">{{ activePR.requester || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Product</p>
                            <p class="font-medium text-slate-900">{{ activePR.product?.name || 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ activePR.product?.sku || '' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Need By Date</p>
                            <p class="font-medium text-slate-900">{{ activePR.expected_delivery_date || 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Quantity Requested</span>
                            <span class="font-bold text-slate-900">{{ activePR.quantity_requested }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Unit Cost</span>
                            <span class="font-medium text-slate-700">₱{{ Number(activePR.unit_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                            <span class="font-bold text-slate-900">Estimated Total</span>
                            <span class="text-lg font-bold text-brand-pink">₱{{ Number(activePR.total_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                        </div>
                    </div>

                    <div v-if="activePR.notes">
                        <p class="text-xs font-bold text-slate-500 uppercase">Notes / Justification</p>
                        <p class="text-sm text-slate-700 mt-1 bg-white border border-slate-200 p-3 rounded-lg">{{ activePR.notes }}</p>
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
