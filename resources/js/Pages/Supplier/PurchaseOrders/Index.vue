<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    orders: Array
});

const activeOrder = ref(null);
const showRejectModal = ref(false);
const showUpdateModal = ref(false);
const showViewModal = ref(false);

const openViewModal = (order) => {
    activeOrder.value = order;
    showViewModal.value = true;
};

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedRows.value = newSet;
};

const rejectForm = useForm({
    status: 'rejected',
    reject_reason: ''
});

const updateForm = useForm({
    status: ''
});

const openRejectModal = (order) => {
    activeOrder.value = order;
    rejectForm.reset();
    showRejectModal.value = true;
};

const openUpdateModal = (order) => {
    activeOrder.value = order;
    updateForm.status = order.status === 'confirmed' ? 'preparing' : 'shipped';
    showUpdateModal.value = true;
};

const acceptOrder = (order) => {
    router.post(route('supplier.purchase-orders.update-status', order.id), {
        status: 'confirmed'
    }, { preserveScroll: true });
};

const submitReject = () => {
    rejectForm.post(route('supplier.purchase-orders.update-status', activeOrder.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showRejectModal.value = false;
        }
    });
};

const submitUpdate = () => {
    updateForm.post(route('supplier.purchase-orders.update-status', activeOrder.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showUpdateModal.value = false;
        }
    });
};

const statusClass = (status) => {
    switch(status) {
        case 'ordered':
        case 'pending': return 'bg-orange-50 text-orange-700 ring-orange-600/20';
        case 'confirmed': return 'bg-blue-50 text-blue-700 ring-blue-600/20';
        case 'preparing': return 'bg-purple-50 text-purple-700 ring-purple-600/20';
        case 'shipped': 
        case 'received':
        case 'partially_received': return 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
        case 'rejected':
        case 'cancelled': return 'bg-red-50 text-red-700 ring-red-600/20';
        default: return 'bg-slate-50 text-slate-700 ring-slate-600/20';
    }
};

let pollInterval = null;
onMounted(() => {
    pollInterval = setInterval(() => {
        router.reload({ only: ['orders'], preserveScroll: true, preserveState: true });
    }, 5000); // 5-second interval for real-time feeling
});
onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<template>
    <AppLayout title="Purchase Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Manage incoming orders from the Inventory Account</p>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">PO Number</th>
                                <th class="py-4 px-4 font-bold">Product</th>
                                <th class="py-4 px-4 font-bold text-center">Qty</th>
                                <th class="py-4 px-4 font-bold text-right">Total Value</th>
                                <th class="py-4 px-4 font-bold">Date</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="order in orders" :key="order.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">{{ order.po_number }}</span>
                                    <button @click="toggleRow(order.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(order.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Product</span>
                                    <span class="text-slate-700">{{ order.product }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Qty</span>
                                    <span class="font-bold text-slate-700">{{ order.qty }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0 md:text-right" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Total Value</span>
                                    <span class="font-black text-slate-900">₱{{ order.value }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Date</span>
                                    <span class="text-slate-700">{{ order.date }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset" :class="statusClass(order.status)">{{ order.status.replace('_', ' ') }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(order.id), 'flex md:table-cell justify-between items-center': expandedRows.has(order.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                    <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <!-- Pending Actions -->
                                                <template v-if="order.status === 'ordered' || order.status === 'pending'">
                                                    <DropdownLink as="button" @click="acceptOrder(order)" class="!text-emerald-600 hover:!bg-emerald-50">
                                                        Accept Order
                                                    </DropdownLink>
                                                    <DropdownLink as="button" @click="openRejectModal(order)" class="!text-red-600 hover:!bg-red-50">
                                                        Reject Order
                                                    </DropdownLink>
                                                </template>
                                                
                                                <!-- Update Actions -->
                                                <template v-if="['confirmed', 'preparing'].includes(order.status)">
                                                    <DropdownLink as="button" @click="openUpdateModal(order)">
                                                        Update Status
                                                    </DropdownLink>
                                                </template>

                                                <DropdownLink as="button" @click="openViewModal(order)">
                                                    View Details
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="orders.length === 0" class="block md:table-row">
                                <td colspan="7" class="py-12 text-center text-sm text-slate-400 block md:table-cell">No purchase orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <DialogModal :show="showRejectModal" @close="showRejectModal = false" maxWidth="md">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Reject Purchase Order</h3>
            </template>
            <template #content>
                <div class="mt-2 space-y-4">
                    <p class="text-sm text-slate-600">Please provide a reason for rejecting <span class="font-bold">{{ activeOrder?.po_number }}</span>.</p>
                    <div>
                        <label class="form-label">Reason</label>
                        <textarea v-model="rejectForm.reject_reason" rows="3" class="form-input" placeholder="e.g., Out of stock, pricing issue..."></textarea>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showRejectModal = false" class="btn-secondary">Cancel</button>
                    <button @click="submitReject" class="btn-primary !bg-red-600 hover:!bg-red-700" :disabled="!rejectForm.reject_reason || rejectForm.processing">Reject Order</button>
                </div>
            </template>
        </DialogModal>

        <!-- Update Status Modal -->
        <DialogModal :show="showUpdateModal" @close="showUpdateModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Update Order Status</h3>
            </template>
            <template #content>
                <div class="mt-2 space-y-4">
                    <p class="text-sm text-slate-600">Update the fulfillment status for <span class="font-bold">{{ activeOrder?.po_number }}</span>.</p>
                    <div>
                        <label class="form-label">New Status</label>
                        <select v-model="updateForm.status" class="form-input">
                            <option value="preparing">Preparing</option>
                            <option value="shipped">Shipped</option>
                        </select>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showUpdateModal = false" class="btn-secondary">Cancel</button>
                    <button @click="submitUpdate" class="btn-primary" :disabled="updateForm.processing">Save Changes</button>
                </div>
            </template>
        </DialogModal>

        <!-- View Details Modal -->
        <DialogModal :show="showViewModal" @close="showViewModal = false" maxWidth="md">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Order Details</h3>
            </template>
            <template #content>
                <div class="mt-4 text-sm text-slate-600 space-y-2">
                    <p>Viewing details for <strong>{{ activeOrder?.po_number }}</strong>.</p>
                    <p>Product: {{ activeOrder?.product }}</p>
                    <p>Qty: {{ activeOrder?.qty }}</p>
                    <p>Total Value: ₱{{ activeOrder?.value }}</p>
                    <p>Date: {{ activeOrder?.date }}</p>
                    <p>Status: <span class="font-bold capitalize">{{ activeOrder?.status?.replace('_', ' ') }}</span></p>
                </div>
            </template>
            <template #footer>
                <button @click="showViewModal = false" class="btn-secondary">Close</button>
            </template>
        </DialogModal>
    </AppLayout>
</template>
