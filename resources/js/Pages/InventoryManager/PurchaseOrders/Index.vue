<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    purchaseOrders: Object,
    stats: Object,
    filters: Object,
    routePrefix: {
        type: String,
        default: 'inventory-manager',
    },
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

watch([search, status], debounce(function ([newSearch, newStatus]) {
    router.get(route(`${props.routePrefix}.purchase-orders.index`), {
        search: newSearch,
        status: newStatus,
    }, { preserveState: true, replace: true });
}, 300));

const getStatusBadge = (status) => {
    if (status === 'ordered') return 'bg-purple-100 text-purple-700';
    if (status === 'partially_received') return 'bg-blue-100 text-blue-700';
    if (status === 'received') return 'bg-green-100 text-green-700';
    if (status === 'overdue') return 'bg-red-100 text-red-700';
    if (status === 'cancelled') return 'bg-gray-100 text-gray-700';
    return 'bg-gray-100 text-gray-700';
};

const expandedActive = ref(new Set());
const toggleActive = (id) => {
    const newSet = new Set(expandedActive.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedActive.value = newSet;
};

const showViewModal = ref(false);
const activePO = ref(null);

const openViewModal = (po) => {
    activePO.value = po;
    showViewModal.value = true;
};
</script>

<template>
    <AppLayout title="Purchase Orders">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <Link :href="route('inventory-manager.dashboard')" class="hover:text-slate-900 transition-colors">Inventory Management</Link>
                        <span class="mx-2">></span>
                        <span class="font-medium text-slate-900">Purchase Orders</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage orders sent to suppliers</p>
                </div>
                <div class="flex gap-3">
                    <button class="btn-secondary flex items-center gap-2 bg-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export
                    </button>
                    <!-- Create PO goes via PR Approval, but if we need a direct button we could add it -->
                </div>
            </div>

            <!-- Summary Widgets -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="glass-card p-4 text-center md:text-left">
                    <div class="text-sm font-medium text-slate-500">Total POs</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ stats.total }}</div>
                </div>
                <div class="glass-card p-4 text-center md:text-left">
                    <div class="text-sm font-medium text-slate-500">Pending</div>
                    <div class="mt-1 text-2xl font-bold text-purple-600">{{ stats.pending }}</div>
                </div>
                <div class="glass-card p-4 text-center md:text-left">
                    <div class="text-sm font-medium text-slate-500">Partially Recv'd</div>
                    <div class="mt-1 text-2xl font-bold text-blue-600">{{ stats.partially_received }}</div>
                </div>
                <div class="glass-card p-4 text-center md:text-left">
                    <div class="text-sm font-medium text-slate-500">Completed</div>
                    <div class="mt-1 text-2xl font-bold text-green-600">{{ stats.completed }}</div>
                </div>
                <div class="glass-card p-4 text-center md:text-left">
                    <div class="text-sm font-medium text-slate-500">Cancelled</div>
                    <div class="mt-1 text-2xl font-bold text-slate-600">{{ stats.cancelled }}</div>
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
                        <input type="text" v-model="search" class="form-input w-full pl-10" placeholder="Search PO number or product..." />
                    </div>
                    
                    <select v-model="status" class="form-input w-full sm:w-auto min-w-[150px]">
                        <option value="">All Status</option>
                        <option value="ordered">Ordered</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="received">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Active POs -->
            <div class="glass-card !p-0 overflow-hidden bg-transparent md:bg-white shadow-none md:shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold text-left">PO Number</th>
                                <th class="py-4 px-4 font-bold text-left">Supplier</th>
                                <th class="py-4 px-4 font-bold text-left">Date</th>
                                <th class="py-4 px-4 font-bold text-center">Items</th>
                                <th class="py-4 px-4 font-bold text-right">Total Cost</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="po in purchaseOrders.data" :key="po.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold">{{ po.po_number }}</span>
                                    <button @click="toggleActive(po.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedActive.has(po.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <span>{{ po.supplier_name }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Date</span>
                                    <span>{{ po.created_at }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Items</span>
                                    <span class="font-bold text-slate-700">{{ po.quantity_ordered }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Cost</span>
                                    <span class="font-bold text-slate-900">₱{{ Number(po.total_cost).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold" :class="getStatusBadge(po.status)">{{ po.status_label }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 md:text-center" :class="{'hidden md:table-cell': !expandedActive.has(po.id), 'flex md:table-cell justify-between items-center': expandedActive.has(po.id)}">
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
                                                <DropdownLink as="button" @click="openViewModal(po)">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink v-if="po.status === 'ordered' || po.status === 'partially_received'" :href="route('inventory-manager.goods-receipts.create', {search: po.po_number})">
                                                    Receive Delivery
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!purchaseOrders.data.length" class="block md:table-row">
                                <td colspan="7" class="py-12 text-center text-slate-500 block md:table-cell">No purchase orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ purchaseOrders.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ purchaseOrders.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ purchaseOrders.total }}</span> results
                </div>
                <div v-if="purchaseOrders.links?.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in purchaseOrders.links" :key="k">
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

        <!-- View PO Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="lg">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Purchase Order Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div v-if="activePO" class="space-y-6 pt-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ activePO.po_number }}</h3>
                            <p class="text-sm text-slate-500">Ordered on {{ activePO.created_at || 'N/A' }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-bold" :class="getStatusBadge(activePO.status)">{{ activePO.status_label }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Supplier</p>
                            <p class="font-medium text-slate-900">{{ activePO.supplier_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Expected Arrival</p>
                            <p class="font-medium text-slate-900">{{ activePO.expected_arrival_date || 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Product</p>
                            <p class="font-medium text-slate-900">{{ activePO.product_name }}</p>
                            <p class="text-xs text-slate-500">{{ activePO.product_sku }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <h4 class="text-sm font-bold text-slate-900 mb-3 border-b border-slate-200 pb-2">Order Summary</h4>
                        
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Unit Cost</span>
                            <span class="font-medium text-slate-700">₱{{ Number(activePO.unit_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Quantity Ordered</span>
                            <span class="font-bold text-slate-900">{{ activePO.quantity_ordered }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-sm text-indigo-600">
                            <span>Total Received</span>
                            <span class="font-bold">{{ activePO.total_received }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                            <span class="font-bold text-slate-900">Total Cost</span>
                            <span class="text-lg font-bold text-brand-pink">₱{{ Number(activePO.total_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                        </div>
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
