<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';

const props = defineProps({
    purchaseRequests: Object,
    filters: Object,
    isManager: Boolean,
    routePrefix: {
        type: String,
        default: 'admin',
    },
    products: Array,
    suppliers: Array,
    prefill: Object,
});

const filters = reactive({ status: props.filters?.status || '', search: props.filters?.search || '' });
const applyFilters = () => router.get(route(`${props.routePrefix}.purchase-requests.index`), filters, { preserveState: true, replace: true });

// --- Modal Form Logic ---
const showCreateModal = ref(false);
const form = useForm({
    supplier_id: props.prefill?.supplier_id ?? '',
    product_id: props.prefill?.product_id ?? '',
    quantity_requested: props.prefill?.quantity ?? '',
    unit_cost: props.prefill?.unit_cost ?? '',
    expected_delivery_date: '',
    notes: '',
    draft_pr_id: props.prefill?.draft_pr_id ?? '',
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
</script>

<template>
    <AppLayout title="Purchase Requests">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <span class="font-medium text-slate-900">Purchase Requests</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
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
                        <input type="text" v-model="filters.search" @input="applyFilters" class="form-input w-full pl-10" placeholder="Search request no., supplier, or requested by..." />
                    </div>
                    
                    <select v-model="filters.status" @change="applyFilters" class="form-input w-full sm:w-auto min-w-[150px]">
                        <option value="">All Status</option>
                        <option value="pending_approval">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="ordered">Ordered</option>
                        <option value="received">Received</option>
                    </select>

                    <select class="form-input w-full sm:w-auto min-w-[160px]">
                        <option value="">All Suppliers</option>
                        <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                    </select>
                    
                    <div class="relative w-full sm:w-auto">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <input type="text" class="form-input w-full pl-10 text-slate-600" value="May 1, 2025 - May 27, 2025" readonly />
                    </div>

                    <button class="btn-secondary flex items-center gap-2 whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        Filters
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="glass-card overflow-hidden !p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold">Request No.</th>
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    Date Requested
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold">Supplier</th>
                                <th class="py-4 px-4 font-bold">Requested By</th>
                                <th class="py-4 px-4 font-bold">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Total Items</th>
                                <th class="py-4 px-4 font-bold text-right">Est. Total (₱)</th>
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    Need By Date
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="pr in purchaseRequests.data" :key="pr.id" class="transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-bold text-slate-900 whitespace-nowrap">{{ formatPRNumber(pr) }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ pr.created_at }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">{{ pr.supplier.name.charAt(0) }}</div>
                                        <span class="font-medium text-slate-700">{{ pr.supplier.name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-700">{{ pr.requester }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset ring-black/5" :class="getStatusBadge(pr.status)">
                                        {{ pr.status_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-700">{{ pr.quantity_requested }}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">₱{{ Number(pr.total_cost).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                                <td class="py-3 px-4 font-medium text-slate-600">{{ pr.expected_delivery_date || '—' }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center items-center gap-1">
                                        <button class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors rounded hover:bg-slate-100" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <button :disabled="!canEdit(pr.status)" :class="canEdit(pr.status) ? 'text-slate-400 hover:text-indigo-600 hover:bg-slate-100' : 'text-slate-200 cursor-not-allowed'" class="p-1.5 transition-colors rounded" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </button>
                                        <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors rounded hover:bg-slate-100" title="More options">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!purchaseRequests.data || !purchaseRequests.data.length">
                                <td colspan="9" class="py-12 text-center text-slate-500">No purchase requests found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="purchaseRequests.total > 0" class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
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
                            <label class="form-label text-sm">Supplier *</label>
                            <select v-model="form.supplier_id" required class="form-select">
                                <option value="" disabled>Select a supplier…</option>
                                <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-sm">Product / SKU *</label>
                            <select v-model="form.product_id" required class="form-select" :disabled="!form.supplier_id">
                                <option value="" disabled>{{ form.supplier_id ? 'Select a product…' : 'Select a supplier first' }}</option>
                                <option v-for="p in availableProducts" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-sm">Quantity *</label>
                                <input v-model="form.quantity_requested" type="number" min="1" required class="form-input" />
                            </div>
                            <div>
                                <label class="form-label text-sm">Need By Date</label>
                                <input v-model="form.expected_delivery_date" type="date" class="form-input" />
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-sm">Notes for Supplier</label>
                            <textarea v-model="form.notes" rows="2" placeholder="Any special instructions…" class="form-input"></textarea>
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
    </AppLayout>
</template>
