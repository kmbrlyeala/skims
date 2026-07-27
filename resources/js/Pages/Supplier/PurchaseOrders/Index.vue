<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    orders: Array,
});

const updateStatus = (orderId, newStatus) => {
    router.post(route('supplier.purchase-orders.update-status', orderId), {
        status: newStatus
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Purchase Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Purchase Orders (From Skims Admin)</h1>
                <p class="mt-1 text-sm text-slate-500">Manage orders sent by the Inventory Manager. Update the status as you pack and ship.</p>
            </div>

            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Active Purchase Orders</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">PO Number</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Quantity</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Total Value</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Delivery Date</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="order in orders" :key="order.id">
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">{{ order.po_number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">{{ order.product_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">{{ order.quantity_ordered }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">${{ order.total_cost }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">{{ order.expected_arrival }}</td>
                                <td class="whitespace-nowrap px-3 py-4">
                                    <select 
                                        :value="order.status" 
                                        @change="updateStatus(order.id, $event.target.value)"
                                        class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm sm:leading-6"
                                        :disabled="order.status === 'received' || order.status === 'delivered'"
                                    >
                                        <option value="ordered">Ordered (Received from Skims)</option>
                                        <option value="partially_received" disabled>Partially Received</option>
                                        <option value="shipped">Shipped (In Transit)</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="received" disabled>Received by Skims</option>
                                    </select>
                                </td>
                            </tr>
                            <tr v-if="orders.length === 0">
                                <td colspan="6" class="px-3 py-4 text-center text-slate-500">No active purchase orders.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
