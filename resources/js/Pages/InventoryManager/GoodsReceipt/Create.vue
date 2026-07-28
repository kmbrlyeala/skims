<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    openPos: Object,
    stats: Object,
    filters: Object,
});

const filters = reactive({ status: props.filters?.status || '', search: props.filters?.search || '' });
const applyFilters = () => router.get(route('inventory-manager.goods-receipts.create'), filters, { preserveState: true, replace: true });

const getStatusBadge = (status) => {
    if (status === 'draft' || status === 'pending' || status === 'ordered') return 'bg-orange-100 text-orange-700';
    if (status === 'partially_received') return 'bg-blue-100 text-blue-700';
    if (status === 'overdue') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-700';
};

const getStatusLabel = (status, label) => {
    if (status === 'ordered') return 'Pending';
    return label;
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

// Side Panel State
const showPanel = ref(false);
const activePo = ref(null);
const activeStep = ref(1);

const showViewModal = ref(false);
const activeViewPo = ref(null);

const openViewModal = (po) => {
    activeViewPo.value = po;
    showViewModal.value = true;
};

const form = useForm({
    purchase_order_id: '',
    quantity_received: 0,
    quantity_damaged: 0,
    invoice_number: '',
    received_at: new Date().toISOString().split('T')[0],
    received_by: 'Inventory Staff',
    notes: '',
});

const openReceivePanel = (po) => {
    activePo.value = po;
    form.purchase_order_id = po.id;
    form.quantity_received = po.remaining_qty; // Default to remaining
    form.quantity_damaged = 0;
    form.invoice_number = '';
    form.notes = '';
    activeStep.value = 1;
    showPanel.value = true;
};

const closePanel = () => {
    showPanel.value = false;
    activePo.value = null;
    form.reset();
};

const pendingQty = computed(() => {
    if (!activePo.value) return 0;
    const ordered = activePo.value.quantity_ordered;
    const previousReceived = activePo.value.total_received;
    const currentReceived = parseInt(form.quantity_received) || 0;
    return Math.max(0, ordered - (previousReceived + currentReceived));
});

const submitReceipt = () => {
    form.post(route('inventory-manager.goods-receipts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closePanel();
        }
    });
};

// Summary Stats
const summaryStats = computed(() => [
    {
        label: 'Pending Deliveries',
        value: props.stats?.pending || 0,
        subtext: 'Awaiting receipt',
        icon: 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
        colorClass: 'text-orange-700',
        bgClass: 'bg-orange-100',
    },
    {
        label: 'Received Today',
        value: props.stats?.received_today || 0,
        subtext: 'Deliveries',
        icon: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        colorClass: 'text-green-600',
        bgClass: 'bg-green-100',
    },
    {
        label: 'Items Received',
        value: props.stats?.items_received || 0,
        subtext: 'Quantity',
        icon: 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
        colorClass: 'text-blue-600',
        bgClass: 'bg-blue-100',
    },
    {
        label: 'Total Value',
        value: `₱${(props.stats?.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}`,
        subtext: 'Today',
        icon: 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        colorClass: 'text-purple-600',
        bgClass: 'bg-purple-100',
    }
]);
</script>

<template>
    <AppLayout title="Receive Deliveries">
        <!-- Slide-over Panel -->
        <div v-if="showPanel" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/50 backdrop-blur-sm transition-opacity" @click.self="closePanel">
            <div class="absolute inset-y-0 right-0 max-w-xl w-full flex">
                <div class="h-full w-full bg-white shadow-2xl flex flex-col transform transition-transform">
                    <!-- Panel Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h2 class="text-xl font-bold text-slate-900">Receive Delivery</h2>
                        <button @click="closePanel" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Panel Content -->
                    <div v-if="activePo" class="flex-1 overflow-y-auto p-6 space-y-8">
                        <!-- PO Details Header -->
                        <div class="bg-slate-50 p-4 rounded-xl grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">PO No.</p>
                                <p class="font-black text-slate-900">{{ activePo.po_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Supplier</p>
                                <p class="font-black text-slate-900">{{ activePo.supplier_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">PO Date</p>
                                <p class="font-medium text-slate-700">{{ activePo.po_date }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Expected Delivery</p>
                                <p class="font-medium text-slate-700">{{ activePo.expected_arrival_date }}</p>
                            </div>
                        </div>

                        <!-- Step Indicator -->
                        <div class="flex items-center justify-between px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#8C5242] text-white flex items-center justify-center text-xs font-bold">1</div>
                                <span class="text-sm font-bold text-[#8C5242]">Receive Items</span>
                            </div>
                            <div class="flex-1 h-px bg-slate-200 mx-4"></div>
                            <div class="flex items-center gap-2 opacity-50">
                                <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">2</div>
                                <span class="text-sm font-bold text-slate-600">Verify</span>
                            </div>
                            <div class="flex-1 h-px bg-slate-200 mx-4"></div>
                            <div class="flex items-center gap-2 opacity-50">
                                <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">3</div>
                                <span class="text-sm font-bold text-slate-600">Complete</span>
                            </div>
                        </div>

                        <!-- Items to Receive -->
                        <div>
                            <h3 class="font-bold text-slate-900 mb-3">Items to Receive</h3>
                            <div class="border border-slate-200 rounded-xl overflow-hidden">
                                <table class="w-full text-left text-sm block md:table">
                                    <thead class="bg-slate-50 border-b border-slate-200 hidden md:table-header-group">
                                        <tr>
                                            <th class="px-4 py-2 font-bold text-slate-600 text-xs">Product</th>
                                            <th class="px-4 py-2 font-bold text-slate-600 text-xs text-center">Ordered</th>
                                            <th class="px-4 py-2 font-bold text-slate-600 text-xs text-center">Received</th>
                                            <th class="px-4 py-2 font-bold text-slate-600 text-xs text-center">Pending</th>
                                        </tr>
                                    </thead>
                                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                                        <tr class="flex flex-col md:table-row">
                                            <td class="px-4 py-3 flex md:table-cell justify-between items-center border-b border-slate-100 md:border-0">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-orange-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5L4.5 7 12 4.5l7.5 2.5L12 9.5zm0 12.5L2 17v-6l10 5 10-5v6l-10 5z"/></svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 leading-tight">{{ activePo.product_name }}</p>
                                                        <p class="text-[10px] text-slate-400 font-mono">{{ activePo.product_sku }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 flex md:table-cell justify-between items-center border-b border-slate-100 md:border-0 md:text-center">
                                                <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Ordered</span>
                                                <span class="font-bold text-slate-700">{{ activePo.quantity_ordered }}</span>
                                            </td>
                                            <td class="px-4 py-3 flex md:table-cell justify-between items-center border-b border-slate-100 md:border-0 md:text-center">
                                                <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Received</span>
                                                <input type="number" v-model="form.quantity_received" min="0" :max="activePo.remaining_qty"
                                                       class="w-24 md:w-16 text-center text-sm font-bold border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1" />
                                            </td>
                                            <td class="px-4 py-3 flex md:table-cell justify-between items-center md:text-center">
                                                <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Pending</span>
                                                <span class="font-bold" :class="pendingQty > 0 ? 'text-red-600' : 'text-green-600'">
                                                    {{ pendingQty }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div>
                            <h3 class="font-bold text-slate-900 mb-3">Delivery Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Invoice / Delivery No.</label>
                                    <input type="text" v-model="form.invoice_number" class="form-input w-full" placeholder="e.g. INV-2025-0527" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Delivery Date</label>
                                    <input type="date" v-model="form.received_at" class="form-input w-full" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Received By</label>
                                    <select v-model="form.received_by" class="form-input w-full">
                                        <option>Inventory Staff</option>
                                        <option>Warehouse Manager</option>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Notes (Optional)</label>
                                    <textarea v-model="form.notes" rows="3" class="form-input w-full" placeholder="All items received in good condition."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Footer -->
                    <div class="p-6 border-t border-slate-100 flex gap-3">
                        <button @click="closePanel" class="flex-1 py-3 px-4 border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="submitReceipt" :disabled="form.processing || form.quantity_received <= 0" 
                                class="flex-1 py-3 px-4 bg-[#8C5242] text-white rounded-lg font-bold hover:bg-[#784638] transition-colors flex items-center justify-center gap-2 disabled:opacity-50">
                            Verify Items
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Page Layout -->
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <span class="font-medium text-slate-900">Purchasing</span>
                        <span class="mx-2">›</span>
                        <span class="font-medium text-slate-900">Receive Deliveries</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Receive Deliveries</h1>
                </div>
            </div>

            <!-- Summary Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="(stat, idx) in summaryStats" :key="idx" class="glass-card flex items-center gap-4 !p-4 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full shrink-0" :class="stat.bgClass">
                        <svg class="h-6 w-6" :class="stat.colorClass" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-500 truncate">{{ stat.label }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight truncate">{{ stat.value }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 truncate">{{ stat.subtext }}</p>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center flex-wrap">
                    <div class="relative w-full sm:w-auto sm:max-w-xs flex-grow">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11zM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" v-model="filters.search" @input="applyFilters" class="form-input w-full pl-10" placeholder="Search PO no., supplier, or reference..." />
                    </div>
                    
                    <select v-model="filters.status" @change="applyFilters" class="form-input w-full sm:w-auto min-w-[150px]">
                        <option value="">All Status</option>
                        <option value="ordered">Pending</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="overdue">Overdue</option>
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
                <div class="p-4 bg-white border-b border-slate-100">
                    <h2 class="font-bold text-slate-900">Purchase Orders Awaiting Delivery</h2>
                </div>
                <div class="overflow-x-auto min-w-full">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    PO No.
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold">Supplier</th>
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    PO Date
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    Expected Delivery
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold">Status</th>
                                <th class="py-4 px-4 font-bold text-right">Total Amount</th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="po in openPos.data" :key="po.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-4 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">{{ po.po_number }}</span>
                                    <button @click="toggleRow(po.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(po.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600 shrink-0">{{ po.supplier_initial || 'S' }}</div>
                                        <p class="font-bold text-slate-700">{{ po.supplier_name }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">PO Date</span>
                                    <span class="whitespace-nowrap font-medium text-slate-600">{{ po.po_date }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Exp. Delivery</span>
                                    <span class="whitespace-nowrap font-medium text-slate-600">{{ po.expected_arrival_date || '—' }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset ring-black/5" :class="getStatusBadge(po.status)">
                                        {{ getStatusLabel(po.status, po.status_label) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Amount</span>
                                    <span class="font-black text-slate-900">₱{{ Number(po.total_cost).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                                </td>
                                <td class="py-4 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
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
                                                <DropdownLink as="button" @click="openReceivePanel(po)" class="!text-[#8C5242] hover:!bg-orange-50 font-bold">
                                                    {{ po.status === 'partially_received' ? 'Continue Receiving' : 'Receive Delivery' }}
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openViewModal(po)">
                                                    View Details
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!openPos.data || !openPos.data.length" class="block md:table-row">
                                <td colspan="7" class="py-12 text-center text-slate-500 block md:table-cell">No pending deliveries found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div v-if="openPos.total > 0" class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ openPos.from || 0 }}</span> to <span class="font-bold text-slate-800">{{ openPos.to || 0 }}</span> of <span class="font-bold text-slate-800">{{ openPos.total }}</span> results
                </div>
                <div v-if="openPos.links?.length > 3">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <template v-for="(link, k) in openPos.links" :key="k">
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
                <div v-if="activeViewPo" class="space-y-6 pt-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ activeViewPo.po_number }}</h3>
                            <p class="text-sm text-slate-500">Ordered on {{ activeViewPo.po_date || 'N/A' }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-bold ring-1 ring-inset ring-black/5" :class="getStatusBadge(activeViewPo.status)">{{ getStatusLabel(activeViewPo.status, activeViewPo.status_label) }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Supplier</p>
                            <p class="font-medium text-slate-900">{{ activeViewPo.supplier_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Expected Arrival</p>
                            <p class="font-medium text-slate-900">{{ activeViewPo.expected_arrival_date || 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Product</p>
                            <p class="font-medium text-slate-900">{{ activeViewPo.product_name }}</p>
                            <p class="text-xs text-slate-500">{{ activeViewPo.product_sku }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <h4 class="text-sm font-bold text-slate-900 mb-3 border-b border-slate-200 pb-2">Order Summary</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Unit Cost</span>
                            <span class="font-medium text-slate-700">₱{{ Number(activeViewPo.unit_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Quantity Ordered</span>
                            <span class="font-bold text-slate-900">{{ activeViewPo.quantity_ordered }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-sm text-indigo-600">
                            <span>Total Received</span>
                            <span class="font-bold">{{ activeViewPo.total_received }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                            <span class="font-bold text-slate-900">Total Cost</span>
                            <span class="text-lg font-bold text-brand-pink">₱{{ Number(activeViewPo.total_cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
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
