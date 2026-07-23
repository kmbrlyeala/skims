<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: Object,
});

const statusClass = (status) => ({
    pending: 'badge-pending',
    processing: 'badge-processing',
    shipped: 'badge-shipped',
    delivered: 'badge-delivered',
    cancelled: 'badge-cancelled',
}[status] || 'badge-draft');
</script>

<template>
    <AppLayout title="Supplier Dashboard">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Supplier Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Your products and sales at a glance</p>
            </div>

            <!-- Stats -->
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

            <!-- Quick Actions + Recent Orders -->
            <div class="grid">
                

                <div class="glass-card lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent Orders</h2>
                        <Link :href="route('supplier.orders')" class="text-xs font-semibold text-pink-600 hover:text-pink-700">View all →</Link>
                    </div>
                    <div v-if="stats.recentOrders?.length" class="space-y-2">
                        <div v-for="item in stats.recentOrders" :key="item.id" class="flex items-center justify-between rounded-xl bg-slate-50/80 px-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ item.inventory_item?.name }}</p>
                                <p class="text-xs text-slate-500">{{ item.order?.customer?.name }} · Qty: {{ item.quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
                                <span class="badge" :class="statusClass(item.order?.status)">{{ item.order?.status }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="py-6 text-center text-sm text-slate-400">No orders yet</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
