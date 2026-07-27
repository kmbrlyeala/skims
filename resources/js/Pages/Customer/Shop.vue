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
        <div class="relative min-h-screen bg-slate-50 pb-20">
            <!-- Dynamic Background elements -->
            <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-pink-50/80 to-transparent z-0"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 space-y-10">
                <div class="text-center max-w-2xl mx-auto">
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight font-sans">Shop Beauty Essentials</h1>
                    <p class="mt-4 text-lg text-slate-600">Curated skincare and self-care products for your glow-up routine.</p>
                </div>

                <!-- Search -->
                <div class="mx-auto max-w-md relative group">
                    <div class="absolute inset-0 bg-pink-400/20 rounded-full blur-md group-hover:bg-pink-400/30 transition-all"></div>
                    <div class="relative bg-white/80 backdrop-blur-xl rounded-full border border-white p-1 flex items-center shadow-lg">
                        <svg class="ml-4 h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input
                            v-model="search"
                            @input="applyFilters"
                            type="text"
                            placeholder="Search our collection..."
                            class="w-full bg-transparent border-0 focus:ring-0 px-4 py-3 text-slate-800 placeholder-slate-400"
                        />
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div v-for="product in products.data" :key="product.id" class="group relative flex flex-col bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden bg-slate-50 relative">
                            <img
                                v-if="product.photo_url"
                                :src="product.photo_url"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div v-else class="flex h-full items-center justify-center text-4xl text-slate-300">✦</div>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <span v-if="product.stock < 1" class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-md">Sold Out</span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col bg-white">
                            <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ product.name }}</h3>
                            <p v-if="product.description" class="mt-2 line-clamp-2 text-sm text-slate-500 leading-relaxed">
                                {{ product.description }}
                            </p>
                            
                            <div class="mt-auto pt-6">
                                <div class="flex items-end justify-between mb-4">
                                    <div>
                                        <p class="text-2xl font-extrabold text-slate-900">₱{{ Number(product.price).toFixed(2) }}</p>
                                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider" :class="product.stock > 0 ? 'text-emerald-500' : 'text-red-500'">
                                            {{ product.stock > 0 ? `${product.stock} Available` : 'Unavailable' }}
                                        </p>
                                    </div>
                                </div>
                                <button
                                    @click="openQuantityModal(product)"
                                    :disabled="product.stock < 1"
                                    class="w-full btn-primary !py-3 !rounded-2xl"
                                >
                                    {{ product.stock < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-if="!products.data?.length" class="py-24 text-center glass-card max-w-2xl mx-auto border-dashed border-2 border-slate-200">
                    <span class="text-5xl mb-4 block">🔍</span>
                    <p class="text-xl font-bold text-slate-900">No products found</p>
                    <p class="mt-2 text-slate-500">We couldn't find anything matching your search term.</p>
                </div>

                <!-- Pagination -->
                <div v-if="products.last_page > 1" class="flex justify-center gap-2 pt-8">
                    <a
                        v-for="link in products.links"
                        :key="link.label"
                        :href="link.url"
                        @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                        class="flex items-center justify-center min-w-[2.5rem] h-10 rounded-xl px-3 text-sm font-bold transition-all"
                        :class="link.active ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-md scale-105' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-pink-300'"
                        v-html="link.label"
                    />
                </div>
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
                    class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Centered Modal Box -->
                <div
                    class="relative z-10 w-full max-w-md overflow-hidden rounded-[2rem] bg-white/90 backdrop-blur-2xl shadow-2xl border border-white transition-all duration-300 my-auto"
                >
                    <!-- Header with Product summary & Close button -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between border-b border-slate-200/60 pb-5">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                                    <img
                                        v-if="selectedProduct.photo_url"
                                        :src="selectedProduct.photo_url"
                                        :alt="selectedProduct.name"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full items-center justify-center text-xl text-slate-300">✦</div>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-lg leading-snug line-clamp-2">{{ selectedProduct.name }}</h4>
                                    <p class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-500 mt-0.5">
                                        ₱{{ Number(selectedProduct.price).toFixed(2) }}
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="closeModal"
                                class="rounded-full p-2 bg-slate-100 text-slate-400 hover:bg-pink-100 hover:text-pink-600 transition-colors shadow-sm"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Quantity Row -->
                        <div class="py-5 space-y-5 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-500 uppercase tracking-wider text-xs">Quantity</span>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center rounded-2xl bg-slate-100 p-1 shadow-inner">
                                        <button
                                            type="button"
                                            @click="decrementQuantity"
                                            :disabled="quantity <= 1"
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm hover:text-pink-600 disabled:opacity-30 transition-all"
                                        >
                                            −
                                        </button>
                                        <input
                                            type="number"
                                            v-model.number="quantity"
                                            min="1"
                                            :max="selectedProduct.stock"
                                            class="h-8 w-12 bg-transparent border-0 p-0 text-center text-sm font-black text-slate-900 focus:ring-0"
                                        />
                                        <button
                                            type="button"
                                            @click="incrementQuantity"
                                            :disabled="quantity >= selectedProduct.stock"
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm hover:text-pink-600 disabled:opacity-30 transition-all"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <span class="text-[10px] font-bold text-pink-500 uppercase tracking-widest bg-pink-50 px-2 py-1 rounded-full">
                                    {{ selectedProduct.stock }} IN STOCK
                                </span>
                            </div>

                            <!-- Total Subtotal Card -->
                            <div class="mt-4 flex items-center justify-between rounded-2xl bg-gradient-to-r from-pink-50 to-rose-50 p-4 border border-pink-100/50 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Subtotal</span>
                                <span class="text-2xl font-black text-slate-900">
                                    ₱{{ ((quantity || 1) * Number(selectedProduct.price)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons (Add To Cart & Buy Now) -->
                    <div class="p-6 pt-0 bg-transparent">
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Add To Cart Button -->
                            <button
                                type="button"
                                @click="confirmAddToCart"
                                :disabled="isSubmitting || selectedProduct.stock < 1"
                                class="flex items-center justify-center gap-2 rounded-2xl border-2 border-pink-100 bg-white py-3.5 text-sm font-bold text-pink-600 transition-all hover:bg-pink-50 hover:border-pink-200 active:scale-95 disabled:opacity-50 shadow-sm"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <span>Add To Cart</span>
                            </button>

                            <!-- Buy Now Button -->
                            <button
                                type="button"
                                @click="buyNow"
                                :disabled="isSubmitting || selectedProduct.stock < 1"
                                class="flex items-center justify-center rounded-2xl bg-gradient-to-r from-pink-500 to-rose-500 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg hover:shadow-pink-500/30 hover:brightness-105 active:scale-95 disabled:opacity-50"
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
