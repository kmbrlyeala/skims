<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    openPos: Array,
    selectedPo: Object,
});

const form = useForm({
    purchase_order_id: props.selectedPo?.id ?? '',
    quantity_received: '',
    quantity_damaged: 0,
    received_at: new Date().toISOString().split('T')[0],
    notes: '',
});

const activePo = computed(() =>
    props.openPos.find(po => po.id == form.purchase_order_id) ?? props.selectedPo ?? null
);

const netReceived = computed(() => {
    const recv = parseInt(form.quantity_received) || 0;
    const dmgd = parseInt(form.quantity_damaged) || 0;
    return Math.max(0, recv - dmgd);
});

const overLimit = computed(() => {
    if (!activePo.value || !form.quantity_received) return false;
    return parseInt(form.quantity_received) > activePo.value.remaining_qty;
});

const submit = () => {
    form.post(route('inventory-manager.goods-receipts.store'));
};
</script>

<template>
    <AppLayout title="Receive Goods">
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('inventory-manager.dashboard')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Receive Goods</h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

                <div v-if="openPos.length === 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="font-semibold text-gray-900 mb-1">No Open Purchase Orders</h3>
                    <p class="text-sm text-gray-400">There are no pending deliveries. Wait for an Admin to approve Purchase Requests.</p>
                </div>

                <form v-else @submit.prevent="submit" class="space-y-6">

                    <!-- Select PO -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Select Purchase Order</h3>
                        <select v-model="form.purchase_order_id" required
                                class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                            <option value="" disabled>Select an open PO…</option>
                            <option v-for="po in openPos" :key="po.id" :value="po.id">
                                {{ po.po_number }} — {{ po.product_name }} ({{ po.remaining_qty }} remaining)
                            </option>
                        </select>
                        <p v-if="form.errors.purchase_order_id" class="text-xs text-red-600">{{ form.errors.purchase_order_id }}</p>

                        <!-- PO Details panel -->
                        <div v-if="activePo" class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-2">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <p class="text-xs text-gray-400">Product</p>
                                    <p class="text-sm font-medium text-gray-900">{{ activePo.product_name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ activePo.product_sku }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Supplier</p>
                                    <p class="text-sm font-medium text-gray-900">{{ activePo.supplier_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Expected</p>
                                    <p class="text-sm font-medium text-gray-900">{{ activePo.expected_arrival_date }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4 pt-2 border-t border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-400">Ordered</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ activePo.quantity_ordered }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Already Received</p>
                                    <p class="text-sm font-semibold text-emerald-600">{{ activePo.total_received }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Remaining</p>
                                    <p class="text-sm font-semibold text-blue-600">{{ activePo.remaining_qty }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Receipt Details</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Received *</label>
                                <input v-model="form.quantity_received" type="number" min="1" required
                                       :max="activePo?.remaining_qty"
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"
                                       :class="overLimit ? 'border-red-300 focus:ring-red-400 focus:border-red-400' : ''" />
                                <p v-if="overLimit" class="text-xs text-red-600 mt-1">
                                    Exceeds remaining quantity of {{ activePo.remaining_qty }}.
                                </p>
                                <p v-if="form.errors.quantity_received" class="text-xs text-red-600 mt-1">{{ form.errors.quantity_received }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Damaged / Rejected</label>
                                <input v-model="form.quantity_damaged" type="number" min="0"
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                            </div>
                        </div>

                        <!-- Net received preview -->
                        <div v-if="form.quantity_received" class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="text-sm text-emerald-700">
                                <strong>{{ netReceived }} units</strong> will be added to on-hand inventory
                                <span v-if="form.quantity_damaged > 0" class="text-emerald-600">
                                    ({{ form.quantity_received }} received − {{ form.quantity_damaged }} damaged)
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Received *</label>
                            <input v-model="form.received_at" type="date" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                            <p v-if="form.errors.received_at" class="text-xs text-red-600 mt-1">{{ form.errors.received_at }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea v-model="form.notes" rows="3" placeholder="Condition of goods, discrepancy notes…"
                                      class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" :disabled="form.processing || overLimit || !form.quantity_received"
                                class="flex-1 py-3 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition disabled:opacity-50">
                            {{ form.processing ? 'Processing…' : 'Confirm Receipt & Update Inventory' }}
                        </button>
                        <Link :href="route('inventory-manager.dashboard')"
                              class="px-6 py-3 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </Link>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        Inventory will update automatically on confirmation. This action is traceable in the PO record.
                    </p>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
