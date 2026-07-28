<script setup>
import { reactive, computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    purchaseOrders: Object,
    stats: Object,
    filters: Object,
    routePrefix: {
        type: String,
        default: 'admin',
    },
});

const filters = reactive({ status: props.filters?.status || '', search: props.filters?.search || '' });
const applyFilters = () => router.get(route(`${props.routePrefix}.purchase-orders.index`), filters, { preserveState: true, replace: true });

const getStatusBadge = (status) => {
    // Map backend statuses to specific UI colors requested
    if (status === 'draft' || status === 'pending') return 'bg-orange-100 text-orange-700';
    if (status === 'ordered') return 'bg-purple-100 text-purple-700';
    if (status === 'partially_received') return 'bg-blue-100 text-blue-700';
    if (status === 'received' || status === 'completed') return 'bg-green-100 text-green-700';
    if (status === 'cancelled') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-700';
};

const getStatusLabel = (status, label) => {
    if (status === 'received') return 'Completed';
    if (status === 'ordered') return 'Ordered'; // Or Pending based on workflow, but standardizing on Ordered for now.
    return label;
};

const formatPRNumber = (prId, createdAt) => {
    if (!prId) return '—';
    const year = new Date(createdAt).getFullYear();
    return `PR-${year}-${String(prId).padStart(4, '0')}`;
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

// Summary Stats
const summaryStats = computed(() => [
    {
        label: 'Total POs',
        value: props.stats?.total || 0,
        subtext: 'All time',
        icon: 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z',
        colorClass: 'text-amber-700',
        bgClass: 'bg-amber-100',
    },
    {
        label: 'Pending',
        value: props.stats?.pending || 0,
        subtext: 'Awaiting delivery',
        icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        colorClass: 'text-orange-600',
        bgClass: 'bg-orange-100',
    },
    {
        label: 'Partially Received',
        value: props.stats?.partially_received || 0,
        subtext: 'In progress',
        icon: 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
        colorClass: 'text-blue-600',
        bgClass: 'bg-blue-100',
    },
    {
        label: 'Completed',
        value: props.stats?.completed || 0,
        subtext: 'Fully received',
        icon: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        colorClass: 'text-green-600',
        bgClass: 'bg-green-100',
    },
    {
        label: 'Cancelled',
        value: props.stats?.cancelled || 0,
        subtext: 'Cancelled orders',
        icon: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        colorClass: 'text-red-600',
        bgClass: 'bg-red-100',
    }
]);
</script>

<template>
    <AppLayout title="Purchase Orders">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <nav class="text-sm text-slate-500 mb-1">
                        <span class="font-medium text-slate-900">Purchase Orders</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button class="btn-secondary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Import
                    </button>
                    <button class="btn-secondary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Export
                    </button>
                    <button class="btn-primary flex items-center gap-2 bg-[#8C5242] hover:bg-[#784638] text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Purchase Order
                    </button>
                </div>
            </div>

            <!-- Summary Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div v-for="(stat, idx) in summaryStats" :key="idx" class="glass-card flex items-center gap-4 !p-4 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full" :class="stat.bgClass">
                        <svg class="h-6 w-6" :class="stat.colorClass" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500">{{ stat.label }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight">{{ stat.value }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ stat.subtext }}</p>
                    </div>
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
                        <input type="text" v-model="filters.search" @input="applyFilters" class="form-input w-full pl-10" placeholder="Search PO no., supplier, or reference..." />
                    </div>
                    
                    <select v-model="filters.status" @change="applyFilters" class="form-input w-full sm:w-auto min-w-[150px]">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="ordered">Ordered</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="received">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <select class="form-input w-full sm:w-auto min-w-[160px]">
                        <option value="">All Suppliers</option>
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
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-4 font-bold flex items-center gap-1 cursor-pointer">
                                    PO No.
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold items-center gap-1 cursor-pointer hidden lg:table-cell">
                                    PO Date
                                    <svg class="w-3 h-3 text-slate-400 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold">Supplier</th>
                                <th class="py-4 px-4 font-bold hidden lg:table-cell">Reference</th>
                                <th class="py-4 px-4 font-bold">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Total Items</th>
                                <th class="py-4 px-4 font-bold text-right hidden md:table-cell">Total Amount (₱)</th>
                                <th class="py-4 px-4 font-bold items-center gap-1 cursor-pointer hidden lg:table-cell">
                                    Expected Delivery
                                    <svg class="w-3 h-3 text-slate-400 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </th>
                                <th class="py-4 px-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100 bg-white">
                            <tr v-for="po in purchaseOrders.data" :key="po.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-4 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0 min-w-[200px]">
                                    <span class="font-bold text-slate-900">{{ po.po_number }}</span>
                                    <button @click="toggleRow(po.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(po.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden lg:table-cell': !expandedRows.has(po.id), 'flex lg:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="lg:hidden text-xs font-bold text-slate-400 uppercase">PO Date</span>
                                    <span class="font-medium text-slate-700 whitespace-nowrap">{{ po.created_at }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Supplier</span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 hidden md:flex items-center justify-center text-sm font-bold text-slate-600 shrink-0">{{ po.supplier_initial || 'S' }}</div>
                                        <div>
                                            <p class="font-bold text-slate-700">{{ po.supplier_name }}</p>
                                            <p class="text-xs text-slate-500">{{ po.supplier_contact || '(02) 8123 4567' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden lg:table-cell': !expandedRows.has(po.id), 'flex lg:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="lg:hidden text-xs font-bold text-slate-400 uppercase">Reference</span>
                                    <span class="font-medium text-slate-600">{{ formatPRNumber(po.pr_id, po.pr_created_at) }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset ring-black/5 whitespace-nowrap" :class="getStatusBadge(po.status)">
                                        {{ getStatusLabel(po.status, po.status_label) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Items</span>
                                    <span class="font-bold text-slate-700">{{ po.quantity_ordered }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Amount</span>
                                    <span class="font-black text-slate-900">₱{{ Number(po.total_cost).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                                </td>
                                <td class="py-4 px-4 border-b border-slate-50 md:border-0" :class="{'hidden lg:table-cell': !expandedRows.has(po.id), 'flex lg:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="lg:hidden text-xs font-bold text-slate-400 uppercase">Expected Delivery</span>
                                    <span class="font-medium text-slate-600">{{ po.expected_arrival_date || '—' }}</span>
                                </td>
                                <td class="py-4 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(po.id), 'flex md:table-cell justify-between items-center': expandedRows.has(po.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Action</span>
                                    <div class="flex justify-end md:justify-center items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink :href="route(`${routePrefix}.purchase-orders.show`, po.id)">
                                                    View Details
                                                </DropdownLink>
                                                <DropdownLink as="button" @click="() => {}">
                                                    Print
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!purchaseOrders.data || !purchaseOrders.data.length" class="block md:table-row">
                                <td colspan="9" class="py-12 text-center text-slate-500 block md:table-cell">No purchase orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="purchaseOrders.total > 0" class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500 mt-6 gap-4">
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
    </AppLayout>
</template>
