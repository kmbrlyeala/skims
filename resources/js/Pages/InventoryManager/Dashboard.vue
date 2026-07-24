<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: Object,
});
</script>

<template>
    <AppLayout title="Inventory Manager Dashboard">
        <div class="page-container space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Inventory Manager Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Warehouse overview and stock metrics</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalProducts }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">{{ stats.activeProducts }} active</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Low Stock SKUs</p>
                    <p class="mt-2 text-3xl font-bold text-pink-600">{{ stats.lowStockCount }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">Needs attention</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Quick Actions -->
                <div class="glass-card">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Quick Actions</h2>
                    <div class="space-y-2">
                        <Link :href="route('inventory-manager.supply-inventory.index')" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                            <span class="text-base"></span> View Supply Inventory
                        </Link>
                        <Link :href="route('inventory-manager.goods-receipts.create')" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                            <span class="text-base"></span> Receive Goods
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
