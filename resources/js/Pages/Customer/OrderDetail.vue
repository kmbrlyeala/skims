<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import DeliveryTracker from '@/Components/DeliveryTracker.vue';
import { ref } from 'vue';

const props = defineProps({
    order: Object,
});

import { usePage } from '@inertiajs/vue3';
const page = usePage();
const flashSuccess = page.props.flash?.success;

// Show success modal automatically if redirected here after placing an order
const showSuccessModal = ref(flashSuccess === 'Order placed successfully!');

// Use local state so the tracking UI is functional/operable for demonstration
const currentStatus = ref(props.order.status);

const closeSuccessModal = () => {
    showSuccessModal.value = false;
};
</script>

<template>
    <CustomerLayout title="Order Details">
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
                <DeliveryTracker :status="currentStatus" @update:status="val => currentStatus = val" />
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
                                v-if="item.product?.photo_url"
                                :src="item.product.photo_url"
                                :alt="item.product?.name"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-lg text-slate-300">✦</div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ item.product?.name }}</p>
                            <p class="text-xs text-slate-500">
                                SKU: {{ item.product?.sku }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-900">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
                            <p class="text-xs text-slate-500">Qty: {{ item.quantity }} × ₱{{ Number(item.price).toFixed(2) }}</p>
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

        <!-- Success Modal -->
        <Teleport to="body">
            <div
                v-if="showSuccessModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="closeSuccessModal"
                ></div>

                <!-- Centered Modal Box -->
                <div
                    class="relative z-10 w-full max-w-sm overflow-hidden rounded-3xl bg-white shadow-2xl border border-slate-100 transition-all duration-300 my-auto text-center"
                >
                    <div class="p-8 pb-6">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 mb-6">
                            <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Payment Success!</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Your payment was successfully processed and your order has been placed.
                        </p>
                        
                        <div class="mt-6 bg-slate-50 rounded-xl p-4 border border-slate-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order Total</span>
                            <span class="text-xl font-extrabold text-slate-900">₱{{ Number(order.total).toFixed(2) }}</span>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <button
                            type="button"
                            @click="closeSuccessModal"
                            class="w-full flex items-center justify-center rounded-xl bg-slate-900 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg hover:bg-slate-800 active:scale-95"
                        >
                            View Order Details
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </CustomerLayout>
</template>
