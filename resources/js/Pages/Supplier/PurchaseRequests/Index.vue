import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showAcceptModal = ref(false);
const showRejectModal = ref(false);
const activeItem = ref(null);

const openAcceptModal = (item) => {
    activeItem.value = item;
    showAcceptModal.value = true;
};

const openRejectModal = (item) => {
    activeItem.value = item;
    showRejectModal.value = true;
};

const isTrackingModalOpen = ref(false);
const activeTrackingPR = ref(null);

const openTrackingModal = (prNumber) => {
    activeTrackingPR.value = prNumber;
    isTrackingModalOpen.value = true;
};

const closeTrackingModal = () => {
    isTrackingModalOpen.value = false;
    setTimeout(() => activeTrackingPR.value = null, 300);
};
</script>

<template>
    <AppLayout title="Incoming Purchase Requests">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">1. Incoming Purchase Requests</h1>
                <p class="mt-1 text-sm text-slate-500 italic">Ito ang pinakamahalagang page.</p>
            </div>

            <div class="glass-card">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">PR No</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Product</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Qty</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Requested By</th>
                                <th class="px-3 py-3.5 text-left font-semibold text-slate-900">Status</th>
                                <th class="px-3 py-3.5 text-right font-semibold text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Pending PR -->
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0012</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">50</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Inventory Manager</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Pending</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openAcceptModal('PR-0012')" class="!text-emerald-600 hover:!bg-emerald-50">
                                                    Accept
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openRejectModal('PR-0012')" class="!text-red-600 hover:!bg-red-50">
                                                    Reject
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Pending PR -->
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0013</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Toner</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">20</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Inventory Manager</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Pending</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex justify-end items-center">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <DropdownLink as="button" @click="openAcceptModal('PR-0013')" class="!text-emerald-600 hover:!bg-emerald-50">
                                                    Accept
                                                </DropdownLink>
                                                <div class="border-t border-slate-100"></div>
                                                <DropdownLink as="button" @click="openRejectModal('PR-0013')" class="!text-red-600 hover:!bg-red-50">
                                                    Reject
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>

                            <!-- Accepted PR (Showing Status Dropdown and Track button) -->
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0011</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Face Wash</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">100</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Inventory Manager</td>
                                <td class="whitespace-nowrap px-3 py-4"><span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Preparing</span></td>
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openTrackingModal('PR-0011')" class="text-xs font-bold text-pink-600 hover:text-pink-800 underline underline-offset-2">Track Status</button>
                                        <select class="block w-full max-w-[140px] rounded-md border-slate-300 py-1.5 pl-3 pr-8 text-xs focus:border-pink-500 focus:outline-none focus:ring-pink-500">
                                            <option disabled>Pending</option>
                                            <option>Accepted</option>
                                            <option>Create Purchase Order</option>
                                            <option selected>Preparing</option>
                                            <option>Shipped</option>
                                            <option>Delivered</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tracking Modal (TikTok Shop Style) -->
        <DialogModal :show="isTrackingModalOpen" @close="closeTrackingModal" maxWidth="sm">
            <template #title>
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Track Request {{ activeTrackingPR }}</h3>
                </div>
            </template>
            <template #content>
                <div class="py-4">
                    <div class="relative border-l-2 border-slate-200 ml-4 space-y-8 pb-4">
                        
                        <!-- Future Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-slate-200 ring-4 ring-white border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-slate-400">Delivered</h4>
                            <p class="mt-1 text-xs text-slate-400">Waiting for delivery.</p>
                        </div>
                        
                        <!-- Future Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-slate-200 ring-4 ring-white border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-slate-400">Shipped</h4>
                            <p class="mt-1 text-xs text-slate-400">Pending courier pickup.</p>
                        </div>

                        <!-- Active Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-pink-500 ring-4 ring-pink-100 shadow-sm border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-pink-600">Preparing</h4>
                            <p class="mt-1 text-xs text-slate-600">Supplier is packing the items.</p>
                            <span class="block mt-1 text-[11px] text-slate-400 font-mono">Jul 25 2026, 14:20</span>
                        </div>

                        <!-- Completed Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-white border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-slate-700">Create Purchase Order</h4>
                            <p class="mt-1 text-xs text-slate-500">Purchase Order #PO-1043 has been generated.</p>
                            <span class="block mt-1 text-[11px] text-slate-400 font-mono">Jul 25 2026, 10:15</span>
                        </div>

                        <!-- Completed Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-white border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-slate-700">Accepted</h4>
                            <p class="mt-1 text-xs text-slate-500">Supplier accepted the purchase request.</p>
                            <span class="block mt-1 text-[11px] text-slate-400 font-mono">Jul 24 2026, 16:45</span>
                        </div>

                        <!-- Completed Step -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] mt-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-white border-2 border-white"></div>
                            <h4 class="text-sm font-bold text-slate-700">Pending</h4>
                            <p class="mt-1 text-xs text-slate-500">Purchase Request created by Inventory Manager.</p>
                            <span class="block mt-1 text-[11px] text-slate-400 font-mono">Jul 24 2026, 15:30</span>
                        </div>

                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeTrackingModal">Close</SecondaryButton>
            </template>
        </DialogModal>
        <!-- Accept Modal -->
        <DialogModal :show="showAcceptModal" @close="showAcceptModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Accept</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to accept <span class="font-bold">{{ activeItem }}</span>?</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showAcceptModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showAcceptModal = false" class="btn-primary !bg-emerald-600 hover:!bg-emerald-700">Accept</button>
                </div>
            </template>
        </DialogModal>

        <!-- Reject Modal -->
        <DialogModal :show="showRejectModal" @close="showRejectModal = false" maxWidth="sm">
            <template #title>
                <h3 class="text-lg font-bold text-slate-900">Confirm Reject</h3>
            </template>
            <template #content>
                <p class="text-sm text-slate-600">Are you sure you want to reject <span class="font-bold">{{ activeItem }}</span>?</p>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="showRejectModal = false" class="btn-secondary">Cancel</button>
                    <button @click="showRejectModal = false" class="btn-primary !bg-red-600 hover:!bg-red-700">Reject</button>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>
