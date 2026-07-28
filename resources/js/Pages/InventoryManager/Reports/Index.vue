<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

const showViewModal = ref(false);
const activeProduct = ref(null);

const openViewModal = (product) => {
    activeProduct.value = product;
    showViewModal.value = true;
};

const props = defineProps({
    reportType: String,
    data: [Array, Object],
});

const setReportType = (type) => {
    router.get(route('inventory-manager.reports.index'), { type }, { preserveState: true });
};

const formatCurrency = (val) => {
    return '₱' + Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const exportCsv = () => {
    window.location.href = route('inventory-manager.reports.index', { type: props.reportType, export: 'csv' });
};

// Donut Chart logic for Categories
const categoryConicGradient = computed(() => {
    if (!props.data?.categories || props.data.categories.length === 0) return 'transparent 0 100%';
    const colors = ['#8C5242', '#D9A05B', '#6B7A6A', '#C3B091', '#5B4B49', '#A28C75'];
    let gradient = [];
    let currentPercentage = 0;
    
    props.data.categories.forEach((cat, index) => {
        const start = currentPercentage;
        const end = currentPercentage + cat.percentage;
        const color = colors[index % colors.length];
        gradient.push(`${color} ${start}% ${end}%`);
        currentPercentage = end;
    });
    
    return gradient.join(', ');
});

const categoryColors = ['#8C5242', '#D9A05B', '#6B7A6A', '#C3B091', '#5B4B49'];

// Donut Chart logic for Statuses
const statusColors = {
    'In Stock': '#10b981', // green-500
    'Low Stock': '#f59e0b', // amber-500
    'Out of Stock': '#ef4444', // red-500
    'Discontinued': '#9ca3af', // gray-400
};

const statusConicGradient = computed(() => {
    if (!props.data?.statuses || props.data.statuses.length === 0) return 'transparent 0 100%';
    let gradient = [];
    let currentPercentage = 0;
    
    props.data.statuses.forEach((stat) => {
        const start = currentPercentage;
        const end = currentPercentage + stat.percentage;
        const color = statusColors[stat.name] || '#ccc';
        gradient.push(`${color} ${start}% ${end}%`);
        currentPercentage = end;
    });
    
    return gradient.join(', ');
});

const getStatusBadge = (status) => {
    if (status === 'In Stock') return 'bg-green-100 text-green-700';
    if (status === 'Low Stock') return 'bg-amber-100 text-amber-700';
    if (status === 'Out of Stock') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <AppLayout title="Inventory Reports">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Inventory Reports</h1>
                    <p class="mt-1 text-sm text-slate-500">View and analyze your inventory performance</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative w-64 hidden sm:block">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="text" class="form-input w-full pl-9 text-slate-600 bg-white" value="May 1, 2025 - May 27, 2025" readonly />
                    </div>
                    <button @click="exportCsv" class="btn-secondary flex items-center gap-2 bg-white">
                        Export
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Report Type Shortcut Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <button @click="setReportType('inventory')" :class="['text-left p-4 rounded-xl border transition-all duration-200 flex flex-col justify-between h-full group', reportType === 'inventory' ? 'border-[#8C5242] bg-[#8C5242]/5 shadow-sm ring-1 ring-[#8C5242]/20' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm']">
                    <div class="flex items-start gap-3">
                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', reportType === 'inventory' ? 'bg-[#8C5242] text-white' : 'bg-[#8C5242]/10 text-[#8C5242]']">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <h3 :class="['font-bold text-sm', reportType === 'inventory' ? 'text-[#8C5242]' : 'text-slate-900']">Inventory Summary</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Overview of total inventory value and quantity</p>
                        </div>
                    </div>
                    <div class="mt-4 self-end text-slate-300 group-hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button @click="setReportType('low_stock')" :class="['text-left p-4 rounded-xl border transition-all duration-200 flex flex-col justify-between h-full group', reportType === 'low_stock' ? 'border-amber-500 bg-amber-50 shadow-sm ring-1 ring-amber-500/20' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm']">
                    <div class="flex items-start gap-3">
                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', reportType === 'low_stock' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-600']">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 :class="['font-bold text-sm', reportType === 'low_stock' ? 'text-amber-700' : 'text-slate-900']">Low Stock Report</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Items that are running low on stock</p>
                        </div>
                    </div>
                    <div class="mt-4 self-end text-slate-300 group-hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button @click="setReportType('movement')" :class="['text-left p-4 rounded-xl border transition-all duration-200 flex flex-col justify-between h-full group', reportType === 'movement' ? 'border-green-500 bg-green-50 shadow-sm ring-1 ring-green-500/20' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm']">
                    <div class="flex items-start gap-3">
                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', reportType === 'movement' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-600']">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h3 :class="['font-bold text-sm', reportType === 'movement' ? 'text-green-700' : 'text-slate-900']">Stock Movement Report</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Track all stock in, out and adjustments</p>
                        </div>
                    </div>
                    <div class="mt-4 self-end text-slate-300 group-hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button @click="setReportType('out_of_stock')" :class="['text-left p-4 rounded-xl border transition-all duration-200 flex flex-col justify-between h-full group', reportType === 'out_of_stock' ? 'border-red-500 bg-red-50 shadow-sm ring-1 ring-red-500/20' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm']">
                    <div class="flex items-start gap-3">
                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', reportType === 'out_of_stock' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-600']">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <h3 :class="['font-bold text-sm', reportType === 'out_of_stock' ? 'text-red-700' : 'text-slate-900']">Out of Stock Report</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Products that are currently out of stock</p>
                        </div>
                    </div>
                    <div class="mt-4 self-end text-slate-300 group-hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button @click="setReportType('purchases')" :class="['text-left p-4 rounded-xl border transition-all duration-200 flex flex-col justify-between h-full group', reportType === 'purchases' ? 'border-purple-500 bg-purple-50 shadow-sm ring-1 ring-purple-500/20' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm']">
                    <div class="flex items-start gap-3">
                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', reportType === 'purchases' ? 'bg-purple-500 text-white' : 'bg-purple-100 text-purple-600']">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <h3 :class="['font-bold text-sm', reportType === 'purchases' ? 'text-purple-700' : 'text-slate-900']">Purchase Report</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Summary of purchases within the period</p>
                        </div>
                    </div>
                    <div class="mt-4 self-end text-slate-300 group-hover:text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            </div>

            <!-- INVENTORY SUMMARY DASHBOARD -->
            <div v-if="reportType === 'inventory' && data?.summary" class="space-y-6">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    Inventory Summary
                    <span class="text-sm font-normal text-slate-500">(May 1, 2025 - May 27, 2025)</span>
                </h2>

                <!-- Top Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Products</p>
                            <p class="text-2xl font-black text-slate-900 mt-0.5">{{ data.summary.total_products }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">All products in inventory</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Stock Quantity</p>
                            <p class="text-2xl font-black text-slate-900 mt-0.5">{{ data.summary.total_stock.toLocaleString() }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">Across all products</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Inventory Value</p>
                            <p class="text-2xl font-black text-slate-900 mt-0.5">{{ formatCurrency(data.summary.total_value) }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">Total value of current stock</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 131.6l8 3.2M12 21.2a9 9 0 110-18 9 9 0 010 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M7 10h10M7 14h10M12 6v12" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Average Stock Value</p>
                            <p class="text-2xl font-black text-slate-900 mt-0.5">{{ formatCurrency(data.summary.average_value) }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">Average value per product</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Stock Value Over Time Mock -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-900 flex items-center gap-1">
                                Stock Value Over Time
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </h3>
                            <select class="form-input text-xs py-1.5 pl-3 pr-8 !rounded-md">
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                        
                        <!-- Line Chart Mock SVG -->
                        <div class="flex-1 relative mt-4">
                            <!-- Y-axis labels -->
                            <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] text-slate-400 font-mono text-right w-8">
                                <span>₱150K</span>
                                <span>₱120K</span>
                                <span>₱90K</span>
                                <span>₱60K</span>
                                <span>₱30K</span>
                                <span>₱0</span>
                            </div>
                            
                            <!-- Grid lines & Chart Area -->
                            <div class="absolute left-10 right-0 top-2 bottom-6 border-b border-slate-200">
                                <!-- Horizontal Grid Lines -->
                                <div class="absolute inset-0 flex flex-col justify-between">
                                    <div class="w-full border-t border-slate-100 border-dashed" v-for="i in 5" :key="i"></div>
                                    <div class="w-full"></div>
                                </div>
                                
                                <!-- Mock SVG Area Chart -->
                                <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 100">
                                    <defs>
                                        <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#8C5242" stop-opacity="0.3" />
                                            <stop offset="100%" stop-color="#8C5242" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                    <!-- Area Fill -->
                                    <path d="M0,80 L10,75 L20,60 L30,65 L40,40 L50,55 L60,45 L70,60 L80,40 L90,20 L100,10 L100,100 L0,100 Z" fill="url(#chartGradient)" />
                                    <!-- Line Path -->
                                    <path d="M0,80 L10,75 L20,60 L30,65 L40,40 L50,55 L60,45 L70,60 L80,40 L90,20 L100,10" fill="none" stroke="#8C5242" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <!-- Data Points -->
                                    <circle cx="10" cy="75" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="20" cy="60" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="30" cy="65" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="40" cy="40" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="50" cy="55" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="60" cy="45" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="70" cy="60" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="80" cy="40" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="90" cy="20" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                    <circle cx="100" cy="10" r="3" fill="white" stroke="#8C5242" stroke-width="2" />
                                </svg>
                            </div>
                            
                            <!-- X-axis labels -->
                            <div class="absolute left-10 right-0 bottom-0 flex justify-between text-[10px] text-slate-400 pt-2">
                                <span>May 1</span>
                                <span>May 6</span>
                                <span>May 11</span>
                                <span>May 16</span>
                                <span>May 21</span>
                                <span>May 27</span>
                            </div>
                        </div>
                    </div>

                    <!-- Top 5 Categories Donut -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col items-center">
                        <h3 class="font-bold text-slate-900 self-start mb-6">Top 5 Categories by Value</h3>
                        
                        <div class="flex items-center gap-8 w-full justify-center">
                            <!-- Donut Graphic -->
                            <div class="relative w-36 h-36 rounded-full shrink-0 shadow-sm" :style="{ background: `conic-gradient(${categoryConicGradient})` }">
                                <!-- Inner Cutout for Donut Shape -->
                                <div class="absolute inset-0 m-auto w-20 h-20 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Categories</span>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex flex-col gap-2">
                                <div v-for="(cat, idx) in data.categories" :key="idx" class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full shrink-0" :style="{ backgroundColor: categoryColors[idx % categoryColors.length] }"></div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700">{{ cat.name }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-500 font-mono">{{ formatCurrency(cat.value) }}</span>
                                            <span class="text-[10px] font-medium text-slate-400">({{ cat.percentage }}%)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-auto pt-6 w-full text-center border-t border-slate-100 hidden"></div>
                        <div class="mt-6 w-full bg-slate-50 rounded-lg py-2.5 text-center text-sm font-bold text-slate-700">
                            Total Value: {{ formatCurrency(data.summary.total_value) }}
                        </div>
                    </div>

                    <!-- Stock Status Overview Donut -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col items-center">
                        <h3 class="font-bold text-slate-900 self-start mb-6">Stock Status Overview</h3>
                        
                        <div class="flex items-center gap-8 w-full justify-center">
                            <!-- Donut Graphic -->
                            <div class="relative w-36 h-36 rounded-full shrink-0 shadow-sm" :style="{ background: `conic-gradient(${statusConicGradient})` }">
                                <!-- Inner Cutout for Donut Shape -->
                                <div class="absolute inset-0 m-auto w-20 h-20 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status</span>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex flex-col gap-2 w-full">
                                <div v-for="stat in data.statuses" :key="stat.name" class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full shrink-0" :style="{ backgroundColor: statusColors[stat.name] }"></div>
                                        <span class="text-xs font-bold text-slate-700">{{ stat.name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-right">
                                        <span class="text-[10px] font-bold text-slate-900">{{ stat.count }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium w-8">({{ stat.percentage }}%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 w-full bg-slate-50 rounded-lg py-2.5 text-center text-sm font-bold text-slate-700">
                            Total Products: {{ data.summary.total_products }}
                        </div>
                    </div>
                </div>

                <!-- Top 10 Products by Value Table -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-900">Top 10 Products by Value</h3>
                        <Link href="#" class="text-xs font-bold text-[#8C5242] bg-[#8C5242]/10 px-3 py-1.5 rounded-lg hover:bg-[#8C5242]/20 transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Full Report
                        </Link>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600 block md:table">
                            <thead class="hidden md:table-header-group">
                                <tr class="bg-slate-50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                                    <th class="py-3 px-5 w-12 text-center">#</th>
                                    <th class="py-3 px-5">Product</th>
                                    <th class="py-3 px-5">Category</th>
                                    <th class="py-3 px-5 text-right">Current Stock</th>
                                    <th class="py-3 px-5 text-right">Unit Cost</th>
                                    <th class="py-3 px-5 text-right">Total Value (₱)</th>
                                    <th class="py-3 px-5 text-center">Stock Status</th>
                                    <th class="py-3 px-5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                                <tr v-for="(item, idx) in data.top_products" :key="item.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                    <td class="hidden md:table-cell py-3 px-5 text-center text-xs font-bold text-slate-400">{{ idx + 1 }}</td>
                                    <td class="py-3 px-5 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-slate-100 rounded border border-slate-200 overflow-hidden shrink-0">
                                                <!-- Mock image placeholder -->
                                                <div class="w-full h-full bg-[#8C5242]/10 flex items-center justify-center text-[#8C5242]/40">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                                </div>
                                            </div>
                                            <span class="font-bold text-slate-900">{{ item.name }}</span>
                                        </div>
                                        <button @click="toggleRow(item.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg">
                                            <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(item.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </td>
                                    <td class="py-3 px-5 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Category</span>
                                        <span class="text-slate-500">{{ item.category }}</span>
                                    </td>
                                    <td class="py-3 px-5 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Current Stock</span>
                                        <span class="font-bold" :class="item.current_stock > 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ item.current_stock.toLocaleString() }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Unit Cost</span>
                                        <span class="text-slate-500 font-mono">{{ formatCurrency(item.unit_cost) }}</span>
                                    </td>
                                    <td class="py-3 px-5 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Value (₱)</span>
                                        <span class="font-black text-slate-900">{{ formatCurrency(item.total_value) }}</span>
                                    </td>
                                    <td class="py-3 px-5 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Stock Status</span>
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset ring-black/5" :class="getStatusBadge(item.status)">
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
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
                                                    <DropdownLink as="button" @click="openViewModal(item)">
                                                        View Product
                                                    </DropdownLink>
                                                </template>
                                            </Dropdown>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!data.top_products?.length" class="block md:table-row">
                                    <td colspan="8" class="py-12 text-center text-slate-500 block md:table-cell">No products found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Legacy Reports Fallback Views -->
            <div v-if="reportType !== 'inventory'" class="glass-card overflow-hidden !p-0 print:border-none print:shadow-none">
                <div class="p-6 border-b border-slate-100 hidden print:block">
                    <h2 class="text-xl font-bold uppercase">{{ reportType.replace('_', ' ') }}</h2>
                    <p class="text-sm text-slate-500">Generated on {{ new Date().toLocaleDateString() }}</p>
                </div>
                
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Detailed Report View</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto">This dedicated report view is under construction. Please use the Inventory Summary for now.</p>
                    <button @click="setReportType('inventory')" class="mt-6 btn-primary">Return to Summary</button>
                </div>
            </div>

        </div>

        <!-- View Product Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Product Report Details</h3>
                    <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
            <template #content>
                <div v-if="activeProduct" class="space-y-6 pt-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-lg border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-[#8C5242]/40">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">{{ activeProduct.name }}</h3>
                                <p class="text-sm text-slate-500">{{ activeProduct.category }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wider ring-1 ring-inset ring-black/5" :class="getStatusBadge(activeProduct.status)">
                            {{ activeProduct.status }}
                        </span>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Current Stock</span>
                            <span class="font-bold" :class="activeProduct.current_stock > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ activeProduct.current_stock.toLocaleString() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Unit Cost</span>
                            <span class="font-medium text-slate-700">{{ formatCurrency(activeProduct.unit_cost) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                            <span class="font-bold text-slate-900">Total Value</span>
                            <span class="text-lg font-black text-slate-900">{{ formatCurrency(activeProduct.total_value) }}</span>
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
