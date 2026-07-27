<script setup>
import { Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

import { ref } from 'vue';

const props = defineProps({
    cartItems: Array,
    total: Number,
});

const isPaymentModalOpen = ref(false);
const paymentMethod = ref('gcash');
const isSubmitting = ref(false);

const updateQuantity = (cartId, quantity) => {
    router.put(route('customer.cart.update', cartId), { quantity }, { preserveState: true });
};

const removeItem = (cartId) => {
    router.delete(route('customer.cart.destroy', cartId), { preserveState: true });
};

const placeOrder = () => {
    isPaymentModalOpen.value = true;
};

const closePaymentModal = () => {
    isPaymentModalOpen.value = false;
};

const submitPayment = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    router.post(route('customer.orders.store'), {
        payment_method: paymentMethod.value,
    }, {
        preserveState: false,
        onSuccess: () => {
            closePaymentModal();
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <CustomerLayout title="Cart">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Shopping Cart</h1>
                <p class="mt-1 text-sm text-slate-500">{{ cartItems.length }} item(s) in your cart</p>
            </div>

            <div v-if="cartItems.length" class="grid gap-6 lg:grid-cols-3">
                <!-- Cart Items -->
                <div class="space-y-3 lg:col-span-2">
                    <div
                        v-for="item in cartItems"
                        :key="item.id"
                        class="flex gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm"
                    >
                        <!-- Image -->
                        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                            <img
                                v-if="item.inventory_item?.image_url"
                                :src="item.inventory_item.image_url"
                                :alt="item.inventory_item.name"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-lg text-slate-300">✦</div>
                        </div>

                        <!-- Details -->
                        <div class="flex flex-1 flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ item.inventory_item?.name }}</h3>
                                <p class="text-xs text-slate-400">{{ item.inventory_item?.supplier?.name }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="item.quantity > 1 ? updateQuantity(item.id, item.quantity - 1) : removeItem(item.id)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-sm text-slate-600 transition-colors hover:bg-slate-50"
                                    >−</button>
                                    <span class="w-8 text-center text-sm font-semibold">{{ item.quantity }}</span>
                                    <button
                                        @click="updateQuantity(item.id, item.quantity + 1)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-sm text-slate-600 transition-colors hover:bg-slate-50"
                                    >+</button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <p class="font-bold text-slate-900">₱{{ (item.inventory_item?.price * item.quantity).toFixed(2) }}</p>
                                    <button
                                        @click="removeItem(item.id)"
                                        class="text-xs font-medium text-red-500 hover:text-red-600"
                                    >Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Order Summary</h3>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-semibold text-slate-900">₱{{ Number(total).toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Shipping</span>
                                <span class="font-semibold text-emerald-600">Free</span>
                            </div>
                            <div class="border-t border-slate-100 pt-2">
                                <div class="flex justify-between">
                                    <span class="font-bold text-slate-900">Total</span>
                                    <span class="text-xl font-bold text-slate-900">₱{{ Number(total).toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>
                        <button @click="placeOrder" class="btn-primary mt-5 w-full">
                            Place Order
                        </button>
                        <Link :href="route('customer.shop')" class="mt-3 block text-center text-xs font-semibold text-pink-600 hover:text-pink-700">
                            ← Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty Cart -->
            <div v-else class="py-16 text-center">
                <p class="text-4xl">🛒</p>
                <p class="mt-3 text-lg font-medium text-slate-500">Your cart is empty</p>
                <Link :href="route('customer.shop')" class="btn-primary mt-4 inline-flex">
                    Browse Products
                </Link>
            </div>
        </div>

        <!-- Payment Method Modal -->
        <Teleport to="body">
            <div
                v-if="isPaymentModalOpen"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="closePaymentModal"
                ></div>

                <!-- Centered Modal Box -->
                <div
                    class="relative z-10 w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl border border-slate-100 transition-all duration-300 my-auto"
                >
                    <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900 text-lg">Select Payment Method</h3>
                        <button
                            @click="closePaymentModal"
                            class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <!-- GCash -->
                            <label class="flex items-center justify-between p-4 border rounded-2xl cursor-pointer transition-all"
                                   :class="paymentMethod === 'gcash' ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' : 'border-slate-200 hover:bg-slate-50'">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs">GC</div>
                                    <span class="font-bold text-slate-700">GCash</span>
                                </div>
                                <input type="radio" value="gcash" v-model="paymentMethod" class="text-blue-500 focus:ring-blue-500 w-5 h-5" />
                            </label>

                            <!-- Maya -->
                            <label class="flex items-center justify-between p-4 border rounded-2xl cursor-pointer transition-all"
                                   :class="paymentMethod === 'maya' ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' : 'border-slate-200 hover:bg-slate-50'">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xs">M</div>
                                    <span class="font-bold text-slate-700">Maya</span>
                                </div>
                                <input type="radio" value="maya" v-model="paymentMethod" class="text-emerald-500 focus:ring-emerald-500 w-5 h-5" />
                            </label>

                            <!-- COD -->
                            <label class="flex items-center justify-between p-4 border rounded-2xl cursor-pointer transition-all"
                                   :class="paymentMethod === 'cod' ? 'border-orange-500 bg-orange-50/50 ring-1 ring-orange-500' : 'border-slate-200 hover:bg-slate-50'">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-slate-800 rounded-full flex items-center justify-center text-white font-bold text-xs">COD</div>
                                    <span class="font-bold text-slate-700">Cash on Delivery</span>
                                </div>
                                <input type="radio" value="cod" v-model="paymentMethod" class="text-orange-500 focus:ring-orange-500 w-5 h-5" />
                            </label>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-between bg-slate-50 p-4 rounded-2xl">
                            <span class="text-sm font-bold text-slate-600">Total to Pay:</span>
                            <span class="text-xl font-extrabold text-orange-600">
                                ₱{{ Number(total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <button
                            type="button"
                            @click="submitPayment"
                            :disabled="isSubmitting"
                            class="w-full flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-emerald-500 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg hover:brightness-105 active:scale-95 disabled:opacity-50"
                        >
                            {{ isSubmitting ? 'Processing...' : 'Confirm Payment' }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </CustomerLayout>
</template>
