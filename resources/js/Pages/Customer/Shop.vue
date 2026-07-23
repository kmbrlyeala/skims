<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    products: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get(route('customer.shop'), {
        search: search.value || undefined,
    }, { preserveState: true, replace: true });
};

const addToCart = (productId) => {
    router.post(route('customer.cart.store'), {
        inventory_item_id: productId,
        quantity: 1,
    }, { preserveState: true });
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
                <div v-for="product in products.data" :key="product.id" class="product-card group">
                    <div class="aspect-square overflow-hidden bg-slate-100">
                        <img
                            v-if="product.image_url"
                            :src="product.image_url"
                            :alt="product.name"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <div v-else class="flex h-full items-center justify-center text-3xl text-slate-300">✦</div>
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-medium text-slate-400">{{ product.supplier?.name }}</p>
                        <h3 class="mt-0.5 font-semibold text-slate-900">{{ product.name }}</h3>
                        <p v-if="product.description" class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">
                            {{ product.description }}
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-lg font-bold text-slate-900">₱{{ Number(product.price).toFixed(2) }}</p>
                            <button
                                @click="addToCart(product.id)"
                                class="rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105"
                            >
                                Add to Cart
                            </button>
                        </div>
                        <p class="mt-1.5 text-[10px] text-slate-400">{{ product.stock }} in stock</p>
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
    </AppLayout>
</template>
