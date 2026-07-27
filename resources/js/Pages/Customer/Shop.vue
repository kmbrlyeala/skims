<script setup>
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    filters: {
        type: Object,
        default: () => ({ search: '' })
    }
});

const search = ref(props.filters.search);
let searchTimeout = null;

const applyFilters = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('customer.shop'),
            { search: search.value },
            { preserveState: true, preserveScroll: true }
        );
    }, 300);
};

// Modal and Quantity state
const selectedProduct = ref(null);
const isModalOpen = ref(false);
const quantity = ref(1);
const isSubmitting = ref(false);

const openQuantityModal = (product) => {
    selectedProduct.value = product;
    quantity.value = 1;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        selectedProduct.value = null;
        quantity.value = 1;
    }, 200);
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
    if (!selectedProduct.value) return;
    
    isSubmitting.value = true;
    router.post(route('customer.cart.add'), {
        product_id: selectedProduct.value.id,
        quantity: quantity.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const buyNow = () => {
    if (!selectedProduct.value) return;
    
    isSubmitting.value = true;
    router.post(route('customer.cart.add'), {
        product_id: selectedProduct.value.id,
        quantity: quantity.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            router.get(route('customer.cart'));
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <CustomerLayout title="Shop">
        <div class="bg-gray-50 min-h-screen pb-20">
            <!-- Header section -->
            <div class="bg-white py-12 text-center border-b border-gray-100 shadow-sm rounded-b-[3rem] mx-2">
                <div class="mt-2 flex justify-center text-sm font-medium text-gray-500 gap-2 mb-4">
                    <Link :href="route('home')" class="hover:text-brand-pink transition">Home</Link>
                    <span>&rsaquo;</span>
                    <span class="text-gray-900 font-bold">Shop</span>
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">All Products</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 space-y-10">
                <!-- Product Grid -->
                <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div v-for="product in products.data" :key="product.id" class="group relative bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300">
                        <!-- Favorite Button -->
                        <button class="absolute top-6 right-6 z-10 h-8 w-8 bg-white rounded-full flex items-center justify-center text-gray-300 hover:text-brand-pink shadow-sm transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>

                        <div @click="openQuantityModal(product)" class="cursor-pointer">
                            <div class="aspect-[4/5] w-full overflow-hidden bg-brand-pink-light rounded-2xl relative mb-4 p-6">
                                <img v-if="product.photo_url" :src="product.photo_url" :alt="product.name" class="h-full w-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500" />
                                <div v-else class="flex h-full items-center justify-center text-4xl text-brand-pink opacity-20">✦</div>
                                
                                <!-- Badges -->
                                <div v-if="product.stock < 1" class="absolute top-4 left-4 bg-gray-900 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Sold Out</div>
                                <div v-else-if="Math.random() > 0.5" class="absolute top-4 left-4 bg-brand-pink text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">20% OFF</div>
                            </div>
                            <div class="px-2">
                                <h3 class="text-base font-bold text-gray-900 line-clamp-1 group-hover:text-brand-pink transition">
                                    {{ product.name }}
                                </h3>
                                <div class="flex items-center mt-1 text-yellow-400 text-xs">
                                    ★★★★★ <span class="text-gray-400 font-medium ml-1.5">(128 reviews)</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-end gap-2">
                                        <p class="text-lg font-black text-gray-900">₱{{ Number(product.price).toFixed(2) }}</p>
                                        <p class="text-xs text-gray-400 line-through mb-1">₱{{ (Number(product.price) * 1.2).toFixed(2) }}</p>
                                    </div>
                                    <button @click.stop="openQuantityModal(product)" class="h-8 w-8 bg-brand-pink text-white rounded-full flex items-center justify-center hover:bg-brand-pink-hover shadow-md transition-transform active:scale-95 disabled:opacity-50" :disabled="product.stock < 1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-if="!products.data?.length" class="py-24 text-center max-w-2xl mx-auto">
                    <p class="text-xl font-bold text-gray-900">No products found</p>
                    <p class="mt-2 text-gray-500 font-medium">We couldn't find anything matching your search term.</p>
                </div>

                <!-- Pagination -->
                <div v-if="products.last_page > 1" class="flex justify-center gap-2 pt-12">
                    <a
                        v-for="link in products.links"
                        :key="link.label"
                        :href="link.url"
                        @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                        class="flex items-center justify-center min-w-[2.5rem] h-10 px-3 text-sm font-bold rounded-xl transition-all"
                        :class="link.active ? 'bg-brand-pink text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm'"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>

        <!-- Product Details Modal (Quick View) -->
        <Teleport to="body">
            <!-- Modal Backdrop -->
            <Transition
                enter-active-class="transition-opacity ease-linear duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity ease-linear duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isModalOpen && selectedProduct"
                    @click="closeModal"
                    class="fixed inset-0 z-[60] bg-gray-900/40 backdrop-blur-sm"
                />
            </Transition>

            <!-- Modal Content -->
            <Transition
                enter-active-class="transition ease-out duration-300 transform"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="transition ease-in duration-200 transform"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div v-if="isModalOpen && selectedProduct" class="fixed inset-0 z-[70] overflow-y-auto pointer-events-none flex items-center justify-center p-4">
                    <div class="relative w-full max-w-4xl bg-white rounded-[2rem] shadow-2xl pointer-events-auto overflow-hidden">
                        
                        <!-- Close button -->
                        <button @click="closeModal" class="absolute top-4 right-4 z-10 p-2 bg-white rounded-full text-gray-400 hover:text-gray-900 shadow-sm transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="grid md:grid-cols-2">
                            <!-- Left: Product Image -->
                            <div class="bg-brand-pink-light p-8 md:p-12 flex flex-col justify-center items-center relative min-h-[300px]">
                                <!-- Favorite inside modal -->
                                <button class="absolute top-6 right-6 h-10 w-10 bg-white rounded-full flex items-center justify-center text-gray-300 hover:text-brand-pink shadow-md transition">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                </button>
                                
                                <img
                                    v-if="selectedProduct.photo_url"
                                    :src="selectedProduct.photo_url"
                                    :alt="selectedProduct.name"
                                    class="w-full h-full max-h-[400px] object-contain mix-blend-multiply"
                                />
                                <div v-else class="text-6xl text-brand-pink opacity-20">✦</div>
                                
                                <!-- Thumbnail gallery placeholder -->
                                <div class="flex gap-2 mt-8 w-full justify-center">
                                    <div class="w-12 h-12 rounded-xl bg-brand-pink/10 border-2 border-brand-pink overflow-hidden"><img :src="selectedProduct.photo_url" class="w-full h-full object-cover mix-blend-multiply"></div>
                                    <div class="w-12 h-12 rounded-xl bg-brand-pink/10 border-2 border-transparent overflow-hidden"><img :src="selectedProduct.photo_url" class="w-full h-full object-cover mix-blend-multiply"></div>
                                    <div class="w-12 h-12 rounded-xl bg-brand-pink/10 border-2 border-transparent overflow-hidden"><img :src="selectedProduct.photo_url" class="w-full h-full object-cover mix-blend-multiply"></div>
                                </div>
                            </div>

                            <!-- Right: Product Details -->
                            <div class="p-8 md:p-10 flex flex-col">
                                <div class="mb-4">
                                    <span class="inline-block px-2 py-1 bg-brand-pink-light text-brand-pink text-[10px] font-bold rounded uppercase tracking-wider mb-3">Best Seller</span>
                                    <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ selectedProduct.name }}</h2>
                                    <div class="flex items-center mt-2 text-yellow-400 text-sm">
                                        ★★★★★ <span class="text-gray-500 font-medium ml-2">(128 reviews)</span>
                                    </div>
                                </div>

                                <div class="flex items-end gap-3 mb-6">
                                    <p class="text-3xl font-black text-gray-900">₱{{ Number(selectedProduct.price).toFixed(2) }}</p>
                                    <p class="text-lg text-gray-400 line-through mb-1">₱{{ (Number(selectedProduct.price) * 1.2).toFixed(2) }}</p>
                                    <span class="text-brand-pink font-bold text-sm mb-1.5 ml-2">20% OFF</span>
                                </div>

                                <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6">
                                    A lightweight formula that deeply hydrates and brings out your natural glow. Suitable for all skin types, gentle and effective.
                                </p>

                                <ul class="space-y-3 mb-8 text-sm font-medium text-gray-600">
                                    <li class="flex items-center gap-2"><svg class="h-5 w-5 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.319 48.319 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg> Intense hydration</li>
                                    <li class="flex items-center gap-2"><svg class="h-5 w-5 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg> Brightens skin</li>
                                    <li class="flex items-center gap-2"><svg class="h-5 w-5 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" /></svg> Smooth and radiant</li>
                                </ul>

                                <div class="mt-auto border-t border-gray-100 pt-6">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="font-bold text-gray-700 text-sm">Quantity</div>
                                        <div class="flex items-center border border-gray-200 rounded-full bg-gray-50 h-10 w-28">
                                            <button @click="decrementQuantity" :disabled="quantity <= 1" class="flex-1 flex justify-center text-gray-500 hover:text-brand-pink disabled:opacity-30 font-bold">−</button>
                                            <input type="number" v-model.number="quantity" class="w-10 text-center bg-transparent border-0 p-0 text-sm font-bold focus:ring-0" readonly />
                                            <button @click="incrementQuantity" :disabled="quantity >= selectedProduct.stock" class="flex-1 flex justify-center text-gray-500 hover:text-brand-pink disabled:opacity-30 font-bold">+</button>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded">In Stock</span>
                                    </div>
                                    
                                    <div class="flex flex-col gap-3">
                                        <button @click="confirmAddToCart" :disabled="isSubmitting || selectedProduct.stock < 1" class="w-full bg-brand-pink text-white py-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-brand-pink-hover shadow-md shadow-pink-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                            Add to Cart
                                        </button>
                                        <button @click="buyNow" :disabled="isSubmitting || selectedProduct.stock < 1" class="w-full bg-white text-gray-900 border border-gray-200 py-4 rounded-xl font-bold text-sm hover:border-gray-900 transition-colors disabled:opacity-50">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Small Info Footer in Modal -->
                                <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4 text-[10px] text-gray-500 font-bold uppercase">
                                    <div class="flex items-center gap-1"><svg class="h-3 w-3 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg> Free Shipping</div>
                                    <div class="flex items-center gap-1"><svg class="h-3 w-3 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg> Easy Returns</div>
                                    <div class="flex items-center gap-1"><svg class="h-3 w-3 text-brand-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg> Secure Payment</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </CustomerLayout>
</template>
