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
    <AppLayout title="My Dashboard">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Welcome back! </h1>
                <p class="mt-1 text-sm text-slate-500">Your beauty essentials dashboard</p>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">My Orders</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalOrders }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">In Cart</p>
                    <p class="mt-2 text-3xl font-bold text-pink-600">{{ stats.cartCount }}</p>
                    <Link :href="route('customer.cart')" class="mt-1 text-xs font-semibold text-pink-600 hover:text-pink-700">View cart →</Link>
                </div>
                <div class="stat-card flex flex-col justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quick Shop</p>
                    <Link :href="route('customer.shop')" class="btn-primary mt-3">
                        Browse Products
                    </Link>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="glass-card">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent Orders</h2>
                    <Link :href="route('customer.orders')" class="text-xs font-semibold text-pink-600 hover:text-pink-700">View all →</Link>
                </div>
                <div v-if="stats.recentOrders?.length" class="space-y-2">
                    <Link
                        v-for="order in stats.recentOrders"
                        :key="order.id"
                        :href="route('customer.orders.show', order.id)"
                        class="flex items-center justify-between rounded-xl bg-slate-50/80 px-4 py-3 transition-colors hover:bg-slate-100"
                    >
                        <div>
                            <p class="text-sm font-medium text-slate-800">Order #{{ order.id }}</p>
                            <p class="text-xs text-slate-500">
                                {{ order.items?.length }} item(s) · {{ new Date(order.created_at).toLocaleDateString() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800">₱{{ Number(order.total).toFixed(2) }}</p>
                            <span class="badge" :class="statusClass(order.status)">{{ order.status }}</span>
                        </div>
                    </Link>
                </div>
                <div v-else class="py-8 text-center">
                    <p class="text-sm text-slate-400">No orders yet</p>
                    <Link :href="route('customer.shop')" class="mt-2 inline-block text-sm font-semibold text-pink-600 hover:text-pink-700">
                        Start shopping →
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
