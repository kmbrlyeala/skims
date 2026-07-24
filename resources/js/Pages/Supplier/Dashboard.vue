<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: Object,
    products: {
        type: Array,
        default: () => [],
    },
    reorderThreshold: {
        type: Number,
        default: 20,
    },
});

const statusClass = (status) => ({
    pending: 'badge-pending',
    processing: 'badge-processing',
    shipped: 'badge-shipped',
    delivered: 'badge-delivered',
    cancelled: 'badge-cancelled',
}[status] || 'badge-draft');

const formatPrice = (value) => Number(value ?? 0).toFixed(2);
const reorderNeeded = (stock) => stock <= props.reorderThreshold;
</script>

<template>
    <AppLayout title="Supplier Dashboard">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Supplier Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Your products and sales at a glance</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">My Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalProducts }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">{{ stats.activeProducts }} active</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Stock</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalStock }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Orders</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalOrders }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">₱{{ Number(stats.totalRevenue).toFixed(2) }}</p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1.5fr_1fr]">
                <div class="glass-card">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Beauty Essentials</h2>
                            <p class="text-xs text-slate-500">Moisturizers, serums, cleansers, sunscreen</p>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">Standardized product data, bulk limits, batch tracking</span>
                    </div>

                    <div v-if="products.length" class="space-y-3">
                        <div v-for="product in products" :key="product.id" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ product.name }}</p>
                                    <p class="text-xs uppercase tracking-wider text-slate-400">{{ product.category || 'Beauty Essentials' }}</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ product.description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-slate-900">Stock: {{ product.stock }}</p>
                                    <p class="text-xs text-slate-500">
                                        Min bulk: {{ product.min_bulk_qty }} · Max bulk: {{ product.max_bulk_qty }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                <div class="rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
                                    <p class="font-medium text-slate-800">Price</p>
                                    ₱{{ formatPrice(product.price) }}
                                </div>
                                <div class="rounded-xl p-3 text-sm"
                                     :class="reorderNeeded(product.stock) ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700'">
                                    <p class="font-medium">{{ reorderNeeded(product.stock) ? 'Reorder needed' : 'Stock healthy' }}</p>
                                    <span class="block text-xs">Restock when below {{ reorderThreshold }}</span>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                <span class="badge badge-info">Batch: {{ product.batch_number || 'N/A' }}</span>
                                <span class="badge badge-info">Expiry: {{ product.batch_expiry || 'TBD' }}</span>
                            </div>
                        </div>
                    </div>

                    <p v-else class="py-6 text-center text-sm text-slate-400">No beauty essentials available.</p>
                </div>

                <div class="glass-card lg:col-span-1">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent Purchase Orders</h2>
                        <Link :href="route('supplier.orders')" class="text-xs font-semibold text-pink-600 hover:text-pink-700">View all →</Link>
                    </div>
                    <div v-if="stats.recentOrders?.length" class="space-y-2">
                        <div v-for="item in stats.recentOrders" :key="item.id" class="flex items-center justify-between rounded-xl bg-slate-50/80 px-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ item.inventory_item?.name }}</p>
                                <p class="text-xs text-slate-500">
                                    PO {{ item.order?.order_number || `#${item.id}` }} · Qty: {{ item.quantity }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
                                <span class="badge" :class="statusClass(item.order?.status)">{{ item.order?.status || 'Pending' }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="py-6 text-center text-sm text-slate-400">No purchase orders yet</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
