<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    orders: Object,
    filters: Object,
});

const statusFilter = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get(route('admin.orders'), {
        status: statusFilter.value || undefined,
    }, { preserveState: true, replace: true });
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
    <AppLayout title="All Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Orders</h1>
                <p class="mt-1 text-sm text-slate-500">All orders across the platform</p>
            </div>

            <!-- Filters -->
            <div>
                <select v-model="statusFilter" @change="applyFilters" class="form-select w-44">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Orders Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders.data" :key="order.id">
                                <td class="font-semibold text-slate-900">#{{ order.id }}</td>
                                <td>
                                    <p class="font-medium text-slate-800">{{ order.customer?.name }}</p>
                                    <p class="text-xs text-slate-400">{{ order.customer?.email }}</p>
                                </td>
                                <td>{{ order.items?.length }} item(s)</td>
                                <td class="font-semibold text-slate-900">₱{{ Number(order.total).toFixed(2) }}</td>
                                <td><span class="badge" :class="statusClass(order.status)">{{ order.status }}</span></td>
                                <td class="text-slate-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="orders.last_page > 1" class="flex items-center justify-between border-t border-slate-100 px-4 py-3">
                    <p class="text-xs text-slate-500">Showing {{ orders.from }}–{{ orders.to }} of {{ orders.total }}</p>
                    <div class="flex gap-1">
                        <a
                            v-for="link in orders.links"
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
