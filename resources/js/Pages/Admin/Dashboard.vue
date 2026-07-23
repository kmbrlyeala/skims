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
    <AppLayout title="Admin Dashboard">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Platform overview and key metrics</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalUsers }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Orders</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalOrders }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">₱{{ Number(stats.totalRevenue).toFixed(2) }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalProducts }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">{{ stats.activeProducts }} active</p>
                </div>
            </div>

            <!-- Quick Actions + Recent Orders -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Quick Actions -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Quick Actions</h2>
                    <div class="space-y-2">
                        <Link :href="route('admin.users')" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                            <span class="text-base"></span> Manage Users
                        </Link>
                        <Link :href="route('admin.orders')" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                            <span class="text-base"></span> View All Orders
                        </Link>
                        <Link :href="route('admin.inventory')" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                            <span class="text-base"></span> View All Inventory
                        </Link>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="glass-card lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent Orders</h2>
                        <Link :href="route('admin.orders')" class="text-xs font-semibold text-pink-600 hover:text-pink-700">View all →</Link>
                    </div>
                    <div v-if="stats.recentOrders?.length" class="space-y-2">
                        <div v-for="order in stats.recentOrders" :key="order.id" class="flex items-center justify-between rounded-xl bg-slate-50/80 px-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-slate-800">Order #{{ order.id }}</p>
                                <p class="text-xs text-slate-500">{{ order.customer?.name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800">₱{{ Number(order.total).toFixed(2) }}</p>
                                <span class="badge" :class="statusClass(order.status)">{{ order.status }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="py-6 text-center text-sm text-slate-400">No orders yet</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
