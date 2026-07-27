<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

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
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-100">Accept</button>
                                        <button class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Reject</button>
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
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-100">Accept</button>
                                        <button class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Reject</button>
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
    </AppLayout>
</template>
