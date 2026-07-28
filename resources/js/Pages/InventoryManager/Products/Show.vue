<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    product: Object,
});
</script>

<template>
    <AppLayout :title="product.name">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('inventory-manager.products.index')" class="text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-2xl font-bold text-slate-900">{{ product.name }}</h1>
                    </div>
                    <p class="mt-1 text-sm text-slate-500 ml-8">SKU: {{ product.sku }} | Category: {{ product.category?.name || 'Uncategorized' }}</p>
                </div>
                <Link :href="route('inventory-manager.supply-inventory.index', { search: product.sku })" class="btn-primary">
                    Manage Stock
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="glass-card md:col-span-1">
                    <img v-if="product.photo_urls?.length" :src="product.photo_urls[0]" class="w-full rounded-lg object-cover" />
                    <div v-else class="flex h-48 w-full items-center justify-center rounded-lg bg-slate-100 text-slate-400">No Image</div>
                    
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Status</p>
                            <p class="font-medium text-slate-900">{{ product.is_active ? 'Active' : 'Inactive' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Price</p>
                            <p class="font-medium text-slate-900">${{ Number(product.price).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card md:col-span-2 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Description</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ product.description || 'No description available.' }}</p>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Stock Batches</h2>
                            <div class="text-sm font-medium">
                                Total On Hand: 
                                <span class="text-indigo-600 text-lg">{{ product.inventory?.on_hand_qty || 0 }}</span>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="py-2 px-3 font-medium">Batch No.</th>
                                        <th class="py-2 px-3 text-right font-medium">Qty</th>
                                        <th class="py-2 px-3 font-medium">Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="batch in product.inventory_batches" :key="batch.id">
                                        <td class="py-2 px-3 font-medium text-slate-900">{{ batch.batch_number || 'N/A' }}</td>
                                        <td class="py-2 px-3 text-right">{{ batch.quantity }}</td>
                                        <td class="py-2 px-3">
                                            {{ batch.expiration_date ? new Date(batch.expiration_date).toLocaleDateString() : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr v-if="!product.inventory_batches?.length">
                                        <td colspan="3" class="py-4 text-center text-slate-500">No stock batches found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
