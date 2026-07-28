<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import DialogModal from '@/Components/DialogModal.vue';

const showViewModal = ref(false);
const activeItem = ref(null);

const openViewModal = (item) => {
    activeItem.value = item;
    showViewModal.value = true;
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) newSet.delete(id);
    else newSet.add(id);
    expandedRows.value = newSet;
};

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
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-4 font-bold text-slate-900">Order #</th>
                                <th class="px-4 py-4 font-bold text-slate-900">Product</th>
                                <th class="px-4 py-4 font-bold text-slate-900">Customer</th>
                                <th class="px-4 py-4 font-bold text-slate-900">Qty</th>
                                <th class="px-4 py-4 font-bold text-slate-900">Total</th>
                                <th class="px-4 py-4 font-bold text-slate-900">Status</th>
                                <th class="px-4 py-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="item in orderItems.data" :key="item.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-semibold text-slate-900">#{{ item.order?.id }}</span>
                                    <button @click="toggleRow(item.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(item.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Product</span>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ item.inventory_item?.name }}</p>
                                        <p class="text-xs text-slate-400">{{ item.inventory_item?.sku }}</p>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Customer</span>
                                    <div>
                                        <p class="text-sm text-slate-700">{{ item.order?.customer?.name }}</p>
                                        <p class="text-xs text-slate-400">{{ item.order?.customer?.email }}</p>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Qty</span>
                                    <span>{{ item.quantity }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total</span>
                                    <span class="font-semibold">₱{{ (item.price * item.quantity).toFixed(2) }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="badge" :class="statusClass(item.order?.status)">{{ item.order?.status }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                    <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openViewModal(item.order?.id)">View Details</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
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

        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Order Details</h3>
            </template>
            <template #content>
                <p class="mt-4 text-sm text-slate-600">Viewing details for Order <strong>#{{ activeItem }}</strong>.</p>
            </template>
            <template #footer>
                <button @click="showViewModal = false" class="btn-secondary">Close</button>
            </template>
        </DialogModal>
    </AppLayout>
</template>
