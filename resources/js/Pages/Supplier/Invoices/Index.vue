<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    invoices: Array
});

const showPaymentModal = ref(false);
const activeItem = ref(null);

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

const openPaymentModal = (item) => {
    activeItem.value = item;
    showPaymentModal.value = true;
};

const markPaid = () => {
    router.post(route('supplier.invoices.mark-paid', activeItem.value.order_id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showPaymentModal.value = false;
        }
    });
};

const statusClass = (status) => {
    if (status === 'Paid') return 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
    if (status === 'Overdue') return 'bg-red-50 text-red-700 ring-red-600/20';
    return 'bg-orange-50 text-orange-700 ring-orange-600/20';
};

let pollInterval = null;
onMounted(() => {
    pollInterval = setInterval(() => {
        router.reload({ only: ['invoices'], preserveScroll: true, preserveState: true });
    }, 5000);
});
onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<template>
    <AppLayout title="Invoices & Billing">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Invoices & Billing</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage your invoices and payment statuses</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Invoice No.</th>
                                <th class="py-4 px-4 font-bold">PO Number</th>
                                <th class="py-4 px-4 font-bold">Amount</th>
                                <th class="py-4 px-4 font-bold">Issue Date</th>
                                <th class="py-4 px-4 font-bold">Due Date</th>
                                <th class="py-4 px-4 font-bold text-center">Status</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="item in invoices" :key="item.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-bold text-slate-900">{{ item.id }}</span>
                                    <button @click="toggleRow(item.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(item.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">PO Number</span>
                                    <span class="text-slate-700">{{ item.po }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Amount</span>
                                    <span class="font-semibold text-slate-900">{{ item.amount }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Issue Date</span>
                                    <span class="text-slate-700">{{ item.date }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Due Date</span>
                                    <span class="text-slate-700">{{ item.due }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Status</span>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset" :class="statusClass(item.status)">{{ item.status }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(item.id), 'flex md:table-cell justify-between items-center': expandedRows.has(item.id)}">
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
                                                <DropdownLink as="a" :href="route('supplier.invoices.download', item.order_id)" target="_blank">
                                                    View PDF
                                                </DropdownLink>
                                                <DropdownLink v-if="item.status !== 'Paid'" as="button" @click="openPaymentModal(item)">
                                                    Mark as Paid
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="invoices.length === 0" class="block md:table-row">
                                <td colspan="7" class="py-12 text-center text-sm text-slate-400 block md:table-cell">No invoices found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mark Paid Modal -->
        <DialogModal :show="showPaymentModal" @close="showPaymentModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Payment</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to mark invoice <span class="font-bold">{{ activeItem?.id }}</span> as Paid?</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showPaymentModal = false" class="btn-secondary">Cancel</button>
                    <button @click="markPaid" class="btn-primary !bg-emerald-600 hover:!bg-emerald-700">Confirm</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
