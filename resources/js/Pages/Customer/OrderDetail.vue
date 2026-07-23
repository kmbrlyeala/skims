<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    order: Object,
});

const statusClass = (status) => ({
    pending: 'badge-pending',
    processing: 'badge-processing',
    shipped: 'badge-shipped',
    delivered: 'badge-delivered',
    cancelled: 'badge-cancelled',
}[status] || 'badge-draft');

const statusSteps = ['pending', 'processing', 'shipped', 'delivered'];
const currentStepIndex = statusSteps.indexOf(props.order.status);
</script>

<template>
    <AppLayout title="Order Details">
        <div class="page-container space-y-6">
            <div class="flex items-center gap-3">
                <Link :href="route('customer.orders')" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Order #{{ order.id }}</h1>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Placed on {{ new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </p>
                </div>
            </div>

            <!-- Status Tracker -->
            <div v-if="order.status !== 'cancelled'" class="glass-card">
                <div class="flex items-center justify-between">
                    <div
                        v-for="(step, i) in statusSteps"
                        :key="step"
                        class="flex flex-1 flex-col items-center"
                    >
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold transition-colors"
                            :class="i <= currentStepIndex
                                ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-sm'
                                : 'bg-slate-100 text-slate-400'"
                        >
                            <svg v-if="i < currentStepIndex" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span v-else>{{ i + 1 }}</span>
                        </div>
                        <p class="mt-1.5 text-xs font-semibold capitalize" :class="i <= currentStepIndex ? 'text-pink-600' : 'text-slate-400'">
                            {{ step }}
                        </p>
                    </div>
                </div>
            </div>
            <div v-else class="flash-error text-center">
                This order has been cancelled.
            </div>

            <!-- Order Items -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Items</h2>
                <div class="space-y-3">
                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex items-center gap-4 rounded-xl bg-slate-50/80 p-3"
                    >
                        <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-white">
                            <img
                                v-if="item.inventory_item?.image_url"
                                :src="item.inventory_item.image_url"
                                :alt="item.inventory_item?.name"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-lg text-slate-300">✦</div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ item.inventory_item?.name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ item.supplier?.name }} · SKU: {{ item.inventory_item?.sku }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-900">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
                            <p class="text-xs text-slate-500">Qty: {{ item.quantity }} × ${{ Number(item.price).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4">
                    <div class="flex justify-between">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="text-xl font-bold text-slate-900">₱{{ Number(order.total).toFixed(2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
