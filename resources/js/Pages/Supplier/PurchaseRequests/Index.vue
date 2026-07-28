<script setup>
import { ref, reactive, watch, onMounted, onUnmounted } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import DialogModal from '@/Components/DialogModal.vue';

const props = defineProps({
    requests: Object,
    filters: Object,
});

let syncInterval = null;

onMounted(() => {
    syncInterval = setInterval(() => {
        router.reload({ only: ['requests'], preserveState: true, preserveScroll: true });
    }, 5000);
});

onUnmounted(() => {
    clearInterval(syncInterval);
});

// Filtering & Sorting
const filters = reactive({
    search: props.filters?.search || '',
    status: props.filters?.status || '',
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || '',
    sort_by: props.filters?.sort_by || 'created_at',
    sort_dir: props.filters?.sort_dir || 'desc'
});

const applyFilters = () => {
    router.get(route('supplier.purchase-requests.index'), filters, { preserveState: true, replace: true });
};

const toggleSort = (column) => {
    if (filters.sort_by === column) {
        filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc';
    } else {
        filters.sort_by = column;
        filters.sort_dir = 'asc';
    }
    applyFilters();
};

const getStatusBadge = (status) => {
    if (status.includes('pending')) return 'bg-orange-100 text-orange-700 ring-orange-500/10';
    if (status.includes('approved')) return 'bg-blue-100 text-blue-700 ring-blue-500/10';
    if (status === 'rejected') return 'bg-red-100 text-red-700 ring-red-500/10';
    return 'bg-gray-100 text-gray-700 ring-gray-500/10';
};

// Actions
const showRejectModal = ref(false);
const activeRequest = ref(null);
const showViewModal = ref(false);

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
    reject_reason: ''
});

const openReject = (pr) => {
    activeRequest.value = pr;
    form.reject_reason = '';
    showRejectModal.value = true;
};

const openView = (pr) => {
    activeRequest.value = pr;
    showViewModal.value = true;
};

const approve = (pr) => {
    router.post(route('supplier.purchase-requests.approve', pr.id), {}, { preserveScroll: true });
};

const reject = () => {
    form.post(route('supplier.purchase-requests.reject', activeRequest.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showRejectModal.value = false;
        }
    });
};
</script>

<template>
    <AppLayout title="Purchase Requests">
        <div class="page-container space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
                    <p class="mt-1 text-sm text-slate-500">Review incoming requests before they become orders</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="glass-card flex flex-col sm:flex-row flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="form-label">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input v-model="filters.search" @keyup.enter="applyFilters" type="text" placeholder="Search request no or product..." class="form-input pl-9 w-full">
                    </div>
                </div>
                
                <div class="w-full sm:w-48">
                    <label class="form-label">Status</label>
                    <select v-model="filters.status" @change="applyFilters" class="form-select w-full">
                        <option value="">All Statuses</option>
                        <option value="pending_factory_approval">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <label class="form-label">Start Date</label>
                    <input v-model="filters.start_date" @change="applyFilters" type="date" class="form-input w-full">
                </div>
                
                <div class="w-full sm:w-40">
                    <label class="form-label">End Date</label>
                    <input v-model="filters.end_date" @change="applyFilters" type="date" class="form-input w-full">
                </div>

                <button @click="applyFilters" class="btn-primary">Filter</button>
            </div>

            <!-- Table -->
            <div class="glass-card overflow-hidden !p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 select-none">
                            <tr>
                                <th @click="toggleSort('id')" class="py-4 px-4 font-bold cursor-pointer hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Request No.
                                        <span v-if="filters.sort_by === 'id'">{{ filters.sort_dir === 'asc' ? '↑' : '↓' }}</span>
                                    </div>
                                </th>
                                <th @click="toggleSort('created_at')" class="py-4 px-4 font-bold cursor-pointer hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Date Requested
                                        <span v-if="filters.sort_by === 'created_at'">{{ filters.sort_dir === 'asc' ? '↑' : '↓' }}</span>
                                    </div>
                                </th>
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold text-center">Qty</th>
                                <th class="py-4 px-4 font-bold text-right">Est Total</th>
                                <th @click="toggleSort('expected_delivery_date')" class="py-4 px-4 font-bold cursor-pointer hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Need By
                                        <span v-if="filters.sort_by === 'expected_delivery_date'">{{ filters.sort_dir === 'asc' ? '↑' : '↓' }}</span>
                                    </div>
                                </th>
                                <th @click="toggleSort('status')" class="py-4 px-4 font-bold cursor-pointer hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Status
                                        <span v-if="filters.sort_by === 'status'">{{ filters.sort_dir === 'asc' ? '↑' : '↓' }}</span>
                                    </div>
                                </th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="pr in requests.data" :key="pr.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">{{ pr.request_no }}</span>
                                    <button @click="toggleRow(pr.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(pr.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Date Requested</span>
                                    <span class="text-slate-700 whitespace-nowrap">{{ pr.date }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Product</span>
                                    <div class="flex flex-col md:block text-right md:text-left">
                                        <span class="font-medium text-slate-700">{{ pr.product }}</span>
                                        <div class="text-xs text-slate-400 font-mono">{{ pr.sku }}</div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Qty</span>
                                    <span class="font-bold text-slate-700">{{ pr.qty }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Est Total</span>
                                    <span class="font-bold text-slate-900">₱{{ pr.value }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Need By</span>
                                    <span class="font-medium text-slate-600">{{ pr.need_by_date || '—' }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset" :class="getStatusBadge(pr.status)">
                                        {{ pr.status_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(pr.id), 'flex md:table-cell justify-between items-center': expandedRows.has(pr.id)}">
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
                                                <DropdownLink as="button" @click="openView(pr)">
                                                    View Details
                                                </DropdownLink>
                                                <div v-if="pr.status.includes('pending')">
                                                    <div class="border-t border-slate-100 my-1"></div>
                                                    <DropdownLink as="button" @click="approve(pr)" class="!text-emerald-600">
                                                        Approve
                                                    </DropdownLink>
                                                    <DropdownLink as="button" @click="openReject(pr)" class="!text-red-600">
                                                        Reject
                                                    </DropdownLink>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!requests.data || !requests.data.length" class="block md:table-row">
                                <td colspan="8" class="py-12 text-center text-slate-500 block md:table-cell">No purchase requests yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div v-if="requests.links && requests.links.length > 3" class="px-4 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <Link v-if="requests.prev_page_url" :href="requests.prev_page_url" class="btn-secondary">Previous</Link>
                        <span v-else class="btn-secondary opacity-50 cursor-not-allowed">Previous</span>
                        
                        <Link v-if="requests.next_page_url" :href="requests.next_page_url" class="btn-secondary">Next</Link>
                        <span v-else class="btn-secondary opacity-50 cursor-not-allowed">Next</span>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-700">
                                Showing <span class="font-medium">{{ requests.from }}</span> to <span class="font-medium">{{ requests.to }}</span> of <span class="font-medium">{{ requests.total }}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <Link v-for="(link, i) in requests.links" :key="i"
                                      :href="link.url || '#'"
                                      :class="[
                                          link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-slate-300 text-slate-500 hover:bg-slate-50',
                                          !link.url ? 'opacity-50 cursor-not-allowed' : '',
                                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors'
                                      ]"
                                      v-html="link.label">
                                </Link>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Details Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="lg">
            <template #title>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Purchase Request Details</h3>
                    <span v-if="activeRequest" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset" :class="getStatusBadge(activeRequest.status)">
                        {{ activeRequest.status_label }}
                    </span>
                </div>
            </template>
            <template #content>
                <div v-if="activeRequest" class="space-y-6 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-slate-500 mb-1">Request No.</span>
                            <span class="font-medium text-slate-900">{{ activeRequest.request_no }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 mb-1">Date Requested</span>
                            <span class="font-medium text-slate-900">{{ activeRequest.date }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 mb-1">Requested By</span>
                            <span class="font-medium text-slate-900">{{ activeRequest.requested_by }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 mb-1">Need By</span>
                            <span class="font-medium text-slate-900">{{ activeRequest.need_by_date || 'Not specified' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <h4 class="font-bold text-slate-900 mb-3">Item Details</h4>
                        <div class="bg-slate-50 p-4 rounded-lg flex justify-between items-center">
                            <div>
                                <div class="font-medium text-slate-900">{{ activeRequest.product }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">SKU: {{ activeRequest.sku }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-lg text-slate-900">{{ activeRequest.qty }} units</div>
                                <div class="text-xs text-slate-500 mt-0.5">Total: ₱{{ activeRequest.value }}</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="activeRequest.status === 'rejected'" class="border-t border-slate-100 pt-4">
                        <h4 class="font-bold text-red-600 mb-2">Rejection Reason</h4>
                        <div class="bg-red-50 text-red-800 p-3 rounded-md text-sm border border-red-100">
                            {{ activeRequest.reject_reason }}
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="showViewModal = false" class="btn-secondary">Close</button>
                    <template v-if="activeRequest && activeRequest.status.includes('pending')">
                        <button type="button" @click="() => { showViewModal = false; openReject(activeRequest); }" class="btn-secondary !text-red-600">Reject</button>
                        <button type="button" @click="() => { showViewModal = false; approve(activeRequest); }" class="btn-primary !bg-emerald-600 hover:!bg-emerald-700">Approve</button>
                    </template>
                </div>
            </template>
        </DialogModal>

        <!-- Reject Modal -->
        <DialogModal :show="showRejectModal" @close="showRejectModal = false" maxWidth="md">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Reject Purchase Request</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-500 mb-4">Please provide a reason for rejecting this purchase request. This will be shared with the inventory team.</p>
                
                <form @submit.prevent="reject">
                    <label class="form-label">Reject Reason *</label>
                    <textarea v-model="form.reject_reason" required rows="3" class="form-input mt-1" placeholder="e.g. Out of stock, cannot meet deadline..."></textarea>
                    <p v-if="form.errors.reject_reason" class="mt-1 text-xs text-red-600">{{ form.errors.reject_reason }}</p>
                </form>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="showRejectModal = false" class="btn-secondary">Cancel</button>
                    <button type="button" @click="reject" :disabled="form.processing" class="btn-primary !bg-red-600 hover:!bg-red-700">
                        {{ form.processing ? 'Rejecting...' : 'Reject Request' }}
                    </button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
