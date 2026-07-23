<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    cartItems: Array,
    total: Number,
});

const updateQuantity = (cartId, quantity) => {
    router.put(route('customer.cart.update', cartId), { quantity }, { preserveState: true });
};

const removeItem = (cartId) => {
    router.delete(route('customer.cart.destroy', cartId), { preserveState: true });
};

const placeOrder = () => {
    router.post(route('customer.orders.store'));
};
</script>

<template>
    <AppLayout title="Cart">
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
    </AppLayout>
</template>
