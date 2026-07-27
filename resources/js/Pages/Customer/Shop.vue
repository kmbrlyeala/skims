<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    products: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const selectedProduct = ref(null);
const quantity = ref(1);
const isModalOpen = ref(false);
const isSubmitting = ref(false);

const applyFilters = () => {
    router.get(route('customer.shop'), {
        search: search.value || undefined,
    }, { preserveState: true, replace: true });
};

const openQuantityModal = (product) => {
    if (product.stock < 1) return;
    selectedProduct.value = product;
    quantity.value = 1;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = null;
    quantity.value = 1;
};

const incrementQuantity = () => {
    if (selectedProduct.value && quantity.value < selectedProduct.value.stock) {
        quantity.value++;
    }
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const confirmAddToCart = () => {
    if (!selectedProduct.value || isSubmitting.value) return;

    let finalQty = Math.max(1, Math.min(quantity.value || 1, selectedProduct.value.stock));

    isSubmitting.value = true;
    router.post(route('customer.cart.store'), {
        product_id: selectedProduct.value.id,
        quantity: finalQty,
    }, {
        preserveState: true,
        onSuccess: () => {
            closeModal();
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const isPaymentModalOpen = ref(false);
const paymentMethod = ref('gcash');

const buyNow = () => {
    if (!selectedProduct.value || isSubmitting.value) return;
    // Instead of posting, open payment modal
    isModalOpen.value = false;
    isPaymentModalOpen.value = true;
};

const closePaymentModal = () => {
    isPaymentModalOpen.value = false;
    selectedProduct.value = null;
    quantity.value = 1;
};

const submitPayment = () => {
    if (!selectedProduct.value || isSubmitting.value) return;

    let finalQty = Math.max(1, Math.min(quantity.value || 1, selectedProduct.value.stock));

    isSubmitting.value = true;
    router.post(route('customer.orders.store'), {
        product_id: selectedProduct.value.id,
        quantity: finalQty,
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
    <AppLayout title="Shop">
        <div class="page-container space-y-6">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-900">Shop Beauty Essentials</h1>
                <p class="mt-1 text-sm text-slate-500">Curated skincare and self-care products</p>
            </div>

            <!-- Search -->
            <div class="mx-auto max-w-md">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        v-model="search"
                        @input="applyFilters"
                        type="text"
                        placeholder="Search products..."
                        class="form-input pl-10"
                    />
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div v-for="product in products.data" :key="product.id" class="product-card group flex flex-col justify-between">
                    <div>
                        <div class="aspect-square overflow-hidden bg-slate-100">
                            <img
                                v-if="product.photo_url"
                                :src="product.photo_url"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                            <div v-else class="flex h-full items-center justify-center text-3xl text-slate-300">✦</div>
                        </div>
                        <div class="p-4 pb-0">
                            <h3 class="mt-0.5 font-semibold text-slate-900">{{ product.name }}</h3>
                            <p v-if="product.description" class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">
                                {{ product.description }}
                            </p>
                        </div>
                    </div>

                    <div class="p-4 pt-3">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-bold text-slate-900">₱{{ Number(product.price).toFixed(2) }}</p>
                            <button
                                @click="openQuantityModal(product)"
                                :disabled="product.stock < 1"
                                class="rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ product.stock < 1 ? 'Out of Stock' : 'Add to Cart' }}
                            </button>
                        </div>
                        <p class="mt-1.5 text-[10px] font-medium" :class="product.stock > 0 ? 'text-slate-400' : 'text-rose-500 font-bold'">
                            {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="!products.data?.length" class="py-16 text-center">
                <p class="text-lg text-slate-400">No products found</p>
                <p class="mt-1 text-sm text-slate-400">Try a different search term</p>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="flex justify-center gap-1">
                <a
                    v-for="link in products.links"
                    :key="link.label"
                    :href="link.url"
                    @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                    class="rounded-lg px-3.5 py-2 text-sm font-medium transition-colors"
                    :class="link.active ? 'bg-pink-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                    v-html="link.label"
                />
            </div>
        </div>

        <!-- E-Commerce Style Centered Dialog Modal -->
        <Teleport to="body">
            <div
                v-if="isModalOpen && selectedProduct"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Centered Modal Box -->
                <div
                    class="relative z-10 w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl border border-slate-100 transition-all duration-300 my-auto"
                >
                    <!-- Header with Product summary & Close button -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3.5">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-2xl bg-slate-50 border border-slate-200/60">
                                    <img
                                        v-if="selectedProduct.photo_url"
                                        :src="selectedProduct.photo_url"
                                        :alt="selectedProduct.name"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full items-center justify-center text-xl text-slate-300">✦</div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base leading-snug line-clamp-2">{{ selectedProduct.name }}</h4>
                                    <p class="text-lg font-extrabold text-orange-600 mt-0.5">
                                        ₱{{ Number(selectedProduct.price).toFixed(2) }}
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="closeModal"
                                class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Quantity Row -->
                        <div class="py-4 space-y-4 text-xs text-slate-600">
                            <div class="flex items-center gap-4">
                                <span class="w-24 flex-shrink-0 font-medium text-slate-400">Quantity</span>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm">
                                        <button
                                            type="button"
                                            @click="decrementQuantity"
                                            :disabled="quantity <= 1"
                                            class="flex h-9 w-9 items-center justify-center text-slate-600 hover:bg-slate-50 disabled:opacity-30 rounded-l-xl"
                                        >
                                            −
                                        </button>
                                        <input
                                            type="number"
                                            v-model.number="quantity"
                                            min="1"
                                            :max="selectedProduct.stock"
                                            class="h-9 w-14 border-y-0 border-x border-slate-200 p-0 text-center text-xs font-bold text-slate-900 focus:ring-0"
                                        />
                                        <button
                                            type="button"
                                            @click="incrementQuantity"
                                            :disabled="quantity >= selectedProduct.stock"
                                            class="flex h-9 w-9 items-center justify-center text-slate-600 hover:bg-slate-50 disabled:opacity-30 rounded-r-xl"
                                        >
                                            +
                                        </button>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        {{ selectedProduct.stock }} IN STOCK
                                    </span>
                                </div>
                            </div>

                            <!-- Total Subtotal Card -->
                            <div class="mt-3 flex items-center justify-between rounded-2xl bg-orange-50/70 p-3.5 border border-orange-100">
                                <span class="text-xs font-semibold text-slate-600">Total Subtotal:</span>
                                <span class="text-lg font-extrabold text-orange-600">
                                    ₱{{ ((quantity || 1) * Number(selectedProduct.price)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons (Add To Cart & Buy Now) -->
                    <div class="border-t border-slate-100 p-6 bg-slate-50/60 rounded-b-3xl">
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Add To Cart Button -->
                            <button
                                type="button"
                                @click="confirmAddToCart"
                                :disabled="isSubmitting || selectedProduct.stock < 1"
                                class="flex items-center justify-center gap-1.5 rounded-xl border border-orange-500 bg-orange-50/90 py-3 text-xs font-bold text-orange-600 transition-all hover:bg-orange-100 active:scale-95 disabled:opacity-50 shadow-sm"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <span>Add To Cart</span>
                            </button>

                            <!-- Buy Now Button -->
                            <button
                                type="button"
                                @click="buyNow"
                                :disabled="isSubmitting || selectedProduct.stock < 1"
                                class="flex items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-rose-500 py-3 text-xs font-bold text-white shadow-md transition-all hover:shadow-lg hover:brightness-105 active:scale-95 disabled:opacity-50"
                            >
                                <span>Buy Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Payment Method Modal -->
        <Teleport to="body">
            <div
                v-if="isPaymentModalOpen && selectedProduct"
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
                                ₱{{ ((quantity || 1) * Number(selectedProduct.price)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
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
    </AppLayout>
</template>
