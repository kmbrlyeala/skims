<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ purchaseOrder: Object });
</script>

<template>
    <AppLayout :title="purchaseOrder.po_number">
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.purchase-orders.index')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">{{ purchaseOrder.po_number }}</h2>
                <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                      :class="{
                        'bg-blue-50 text-blue-700': purchaseOrder.status === 'ordered',
                        'bg-amber-50 text-amber-700': purchaseOrder.status === 'partially_received',
                        'bg-emerald-50 text-emerald-700': purchaseOrder.status === 'received',
                        'bg-red-50 text-red-700': purchaseOrder.status === 'cancelled',
                      }">
                    {{ purchaseOrder.status_label }}
                </span>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Ordered</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ purchaseOrder.quantity_ordered }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Received</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ purchaseOrder.total_received }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Remaining</p>
                        <p class="text-2xl font-bold mt-1"
                           :class="purchaseOrder.remaining_qty > 0 ? 'text-blue-600' : 'text-gray-400'">
                            {{ purchaseOrder.remaining_qty }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Total Cost</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ Number(purchaseOrder.total_cost).toLocaleString() }}</p>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- PO Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Order Details</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Product</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ purchaseOrder.product.name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">SKU</dt>
                                <dd class="text-sm font-mono text-gray-700">{{ purchaseOrder.product.sku }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Supplier</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ purchaseOrder.supplier_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Unit Cost</dt>
                                <dd class="text-sm text-gray-900">₱{{ Number(purchaseOrder.unit_cost).toLocaleString() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Expected Arrival</dt>
                                <dd class="text-sm text-gray-900">{{ purchaseOrder.expected_arrival_date }}</dd>
                            </div>
                            <div class="border-t border-gray-100 pt-3">
                                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-2">PR Audit Trail</p>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Requested by</dt>
                                    <dd class="text-sm text-gray-900">{{ purchaseOrder.purchase_request.requester }}</dd>
                                </div>
                                <div v-if="purchaseOrder.purchase_request.approver" class="flex justify-between mt-2">
                                    <dt class="text-sm text-gray-500">Approved by</dt>
                                    <dd class="text-sm text-gray-900">{{ purchaseOrder.purchase_request.approver }} on {{ purchaseOrder.purchase_request.approved_at }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <!-- Current Inventory -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Current Inventory — {{ purchaseOrder.product.sku }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <span class="text-sm text-gray-600">On Hand</span>
                                <span class="text-lg font-bold text-gray-900">{{ purchaseOrder.product.on_hand_qty }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                <span class="text-sm text-blue-600">Incoming (all open POs)</span>
                                <span class="text-lg font-bold text-blue-700">{{ purchaseOrder.product.incoming_qty }}</span>
                            </div>
                        </div>

                        <!-- Record Goods Receipt removed from Admin view -->
                    </div>
                </div>

                <!-- Goods Receipts History -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Goods Receipts</h3>
                    <div v-if="purchaseOrder.goods_receipts.length === 0" class="text-center py-8 text-gray-400 text-sm">
                        No goods received yet.
                    </div>
                    <table v-else class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Received</th>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Damaged</th>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Net</th>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Received By</th>
                                <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="gr in purchaseOrder.goods_receipts" :key="gr.id">
                                <td class="py-3 text-sm text-gray-700">{{ gr.received_at }}</td>
                                <td class="py-3 text-sm font-semibold text-gray-900">{{ gr.quantity_received }}</td>
                                <td class="py-3 text-sm text-red-600">{{ gr.quantity_damaged > 0 ? gr.quantity_damaged : '—' }}</td>
                                <td class="py-3 text-sm font-semibold text-emerald-600">{{ gr.net_received }}</td>
                                <td class="py-3 text-sm text-gray-700">{{ gr.receiver }}</td>
                                <td class="py-3 text-sm text-gray-500">{{ gr.notes ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
