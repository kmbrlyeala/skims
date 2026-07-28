<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: Object,
    recentMovements: Array,
    notifications: Array,
    lowStockList: Array,
});
</script>

<template>
    <AppLayout title="Inventory Dashboard">
        <div class="space-y-6">
            
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
                <div class="text-sm text-slate-500">
                    {{ new Date().toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.totalProducts }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium text-slate-500">Low Stock</p>
                    <p class="mt-2 text-3xl font-bold text-orange-600">{{ stats.lowStockProducts }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium text-slate-500">Out of Stock</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ stats.outOfStockProducts }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium text-slate-500">Pending Deliveries</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ stats.pendingDeliveries }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium text-slate-500">Stock Value</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">₱{{ Number(stats.stockValue).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) }}</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-1 gap-6">
                <!-- Recent Movements -->
                <div class="rounded-xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between p-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-800">Recent Stock Movements</h2>
                        <Link :href="route('inventory-manager.reports.index', { type: 'movement' })" class="text-sm text-blue-600 hover:underline">View All</Link>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead>
                                <tr class="bg-slate-50 text-xs font-semibold text-slate-500 border-b border-slate-100">
                                    <th class="py-3 px-5">Product</th>
                                    <th class="py-3 px-5">Type</th>
                                    <th class="py-3 px-5">Qty</th>
                                    <th class="py-3 px-5">Date</th>
                                    <th class="py-3 px-5">User</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="movement in recentMovements" :key="movement.id" class="hover:bg-slate-50/50">
                                    <td class="py-3 px-5 font-medium text-slate-800">{{ movement.product?.name }}</td>
                                    <td class="py-3 px-5">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold"
                                              :class="{
                                                'bg-green-100 text-green-700': movement.type === 'receive' || movement.type === 'add',
                                                'bg-red-100 text-red-700': movement.type === 'deduct',
                                                'bg-slate-100 text-slate-700': movement.type === 'adjust'
                                              }">
                                            {{ (movement.type === 'receive' || movement.type === 'add') ? 'Added' : (movement.type === 'deduct' ? 'Deducted' : 'Adjusted') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 font-medium">{{ movement.quantity }}</td>
                                    <td class="py-3 px-5 text-slate-500">
                                        {{ new Date(movement.created_at).toLocaleDateString(undefined, {month:'short', day:'numeric'}) }}
                                    </td>
                                    <td class="py-3 px-5">{{ movement.user?.name || 'System' }}</td>
                                </tr>
                                <tr v-if="!recentMovements.length">
                                    <td colspan="5" class="py-8 text-center text-slate-500">No recent movements.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="rounded-xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-800">Low Stock Products</h2>
                    <Link :href="route('inventory-manager.reports.index', { type: 'low_stock' })" class="text-sm text-blue-600 hover:underline">View Report</Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-semibold text-slate-500 border-b border-slate-100">
                                <th class="py-3 px-5">Product</th>
                                <th class="py-3 px-5">SKU</th>
                                <th class="py-3 px-5 text-center">Current Stock</th>
                                <th class="py-3 px-5 text-center">Min Stock</th>
                                <th class="py-3 px-5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="product in lowStockList" :key="product.id" class="hover:bg-slate-50/50">
                                <td class="py-3 px-5">
                                    <span class="font-medium text-slate-800">{{ product.name }}</span>
                                </td>
                                <td class="py-3 px-5 text-slate-500">{{ product.sku }}</td>
                                <td class="py-3 px-5 font-bold text-center" :class="(product.inventory?.on_hand_qty || 0) === 0 ? 'text-red-600' : 'text-orange-600'">
                                    {{ product.inventory?.on_hand_qty || 0 }}
                                </td>
                                <td class="py-3 px-5 text-center text-slate-500">{{ product.inventory?.reorder_point || 0 }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold"
                                          :class="(product.inventory?.on_hand_qty || 0) === 0 ? 'bg-red-50 text-red-700' : 'bg-orange-50 text-orange-700'">
                                        {{ (product.inventory?.on_hand_qty || 0) === 0 ? 'Out of Stock' : 'Low Stock' }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!lowStockList?.length">
                                <td colspan="5" class="py-8 text-center text-slate-500">No low stock items.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
