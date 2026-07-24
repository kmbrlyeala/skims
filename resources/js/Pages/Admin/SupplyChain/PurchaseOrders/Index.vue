<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    purchaseOrders: Object,
    filters: Object,
});

const filters = reactive({ status: '', search: '' });
const applyFilters = () => router.get(route('admin.purchase-orders.index'), filters, { preserveState: true, replace: true });

const statusBadge = (color) => ({
    blue:   'bg-blue-50 text-blue-700',
    amber:  'bg-amber-50 text-amber-700',
    green:  'bg-emerald-50 text-emerald-700',
    red:    'bg-red-50 text-red-700',
}[color] || 'bg-gray-100 text-gray-600');
</script>

<template>
    <AppLayout title="Purchase Orders">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Purchase Orders</h2>
                <p class="text-sm text-gray-400">Created automatically when a Purchase Request is approved.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3">
                    <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search PO number or product…"
                           class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    <select v-model="filters.status" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Statuses</option>
                        <option value="ordered">Ordered</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">PO Number</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ordered</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Received</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Remaining</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expected</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="purchaseOrders.data.length === 0">
                                <td colspan="10" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No purchase orders yet. Approve a Purchase Request to generate one.
                                </td>
                            </tr>
                            <tr v-for="po in purchaseOrders.data" :key="po.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-mono font-medium text-gray-900">{{ po.po_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ po.product_name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ po.product_sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ po.supplier_name }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ po.quantity_ordered }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-600 font-semibold">{{ po.total_received }}</td>
                                <td class="px-6 py-4 text-sm font-semibold"
                                    :class="po.remaining_qty > 0 ? 'text-blue-600' : 'text-gray-400'">
                                    {{ po.remaining_qty }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">₱{{ Number(po.total_cost).toLocaleString() }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ po.expected_arrival_date }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                                          :class="statusBadge(po.status_color)">
                                        {{ po.status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('admin.purchase-orders.show', po.id)"
                                              class="text-xs text-gray-500 hover:text-accent px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                                            View
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="purchaseOrders.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing {{ purchaseOrders.from }} – {{ purchaseOrders.to }} of {{ purchaseOrders.total }}</p>
                        <div class="flex gap-2">
                            <Link v-if="purchaseOrders.prev_page_url" :href="purchaseOrders.prev_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</Link>
                            <Link v-if="purchaseOrders.next_page_url" :href="purchaseOrders.next_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
