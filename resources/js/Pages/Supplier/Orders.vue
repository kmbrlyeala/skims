<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    orderItems: Object,
});

const updateStatus = (orderItem, newStatus) => {
    router.put(route('supplier.orders.updateStatus', orderItem.id), {
        status: newStatus,
    }, { preserveState: true });
};

const statusClass = (status) => ({
    pending: 'badge-pending',
    processing: 'badge-processing',
    shipped: 'badge-shipped',
    delivered: 'badge-delivered',
    cancelled: 'badge-cancelled',
}[status] || 'badge-draft');
</script>

<template>
    <AppLayout title="My Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Orders containing your products</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in orderItems.data" :key="item.id">
                                <td class="font-semibold text-slate-900">#{{ item.order?.id }}</td>
                                <td>
                                    <p class="font-medium text-slate-800">{{ item.inventory_item?.name }}</p>
                                    <p class="text-xs text-slate-400">{{ item.inventory_item?.sku }}</p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-700">{{ item.order?.customer?.name }}</p>
                                    <p class="text-xs text-slate-400">{{ item.order?.customer?.email }}</p>
                                </td>
                                <td>{{ item.quantity }}</td>
                                <td class="font-semibold">₱{{ (item.price * item.quantity).toFixed(2) }}</td>
                                <td><span class="badge" :class="statusClass(item.order?.status)">{{ item.order?.status }}</span></td>
                                <td>
                                    <select
                                        :value="item.order?.status"
                                        @change="updateStatus(item, $event.target.value)"
                                        class="form-select w-32 py-1.5 text-xs"
                                        :disabled="item.order?.status === 'delivered' || item.order?.status === 'cancelled'"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="!orderItems.data?.length" class="py-12 text-center text-sm text-slate-400">
                    No orders for your products yet
                </div>

                <div v-if="orderItems.last_page > 1" class="flex items-center justify-between border-t border-slate-100 px-4 py-3">
                    <p class="text-xs text-slate-500">Showing {{ orderItems.from }}–{{ orderItems.to }} of {{ orderItems.total }}</p>
                    <div class="flex gap-1">
                        <a
                            v-for="link in orderItems.links"
                            :key="link.label"
                            :href="link.url"
                            @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                            :class="link.active ? 'bg-pink-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
