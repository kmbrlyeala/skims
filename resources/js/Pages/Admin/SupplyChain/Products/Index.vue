<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    products: Object,
    filters: Object,
});

const stockBadge = (status) => ({
    ok:          { text: 'In Stock',     cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    low:         { text: 'Low Stock',    cls: 'bg-amber-50 text-amber-700 border-amber-200' },
    out_of_stock:{ text: 'Out of Stock', cls: 'bg-red-50 text-red-700 border-red-200' },
}[status] ?? { text: status, cls: 'bg-gray-100 text-gray-600 border-gray-200' });

const filters = reactive({ search: '', status: '', stock: '' });
const applyFilters = () => router.get(route('admin.products.index'), filters, { preserveState: true, replace: true });
</script>

<template>
    <AppLayout title="Products">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
                <Link :href="route('admin.products.create')"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3">
                    <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search name or SKU…"
                           class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    <select v-model="filters.status" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Listings</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <select v-model="filters.stock" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Stock</option>
                        <option value="in">In Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>

                <!-- Product Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">On Hand</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Incoming</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reorder At</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Listing</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="products.data.length === 0">
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="mx-auto max-w-sm space-y-3">
                                        <p class="text-sm font-medium text-slate-400">No products found in catalog.</p>
                                        <div>
                                            <Link
                                                :href="route('admin.products.create')"
                                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105 active:scale-95"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Create Your First Product
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div v-if="product.photo_url" class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                            <img :src="product.photo_url" class="w-full h-full object-cover" />
                                        </div>
                                        <div v-else class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ product.name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ product.sku }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span v-if="product.supplier_name">{{ product.supplier_name }}</span>
                                    <span v-else class="text-gray-400 italic">No supplier</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">₱{{ Number(product.price).toLocaleString() }}</td>
                                <td class="px-6 py-4 text-sm font-semibold"
                                    :class="product.live_stock === 0 ? 'text-red-600' : product.stock_status === 'low' ? 'text-amber-600' : 'text-gray-900'">
                                    {{ product.live_stock }}
                                </td>
                                <td class="px-6 py-4 text-sm text-blue-600 font-medium">{{ product.incoming_qty }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ product.reorder_point }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full border"
                                          :class="stockBadge(product.stock_status).cls">
                                        {{ stockBadge(product.stock_status).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                                          :class="product.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                        {{ product.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link
                                        :href="route('admin.products.edit', product.id)"
                                        class="inline-flex items-center gap-1 rounded-lg border border-pink-200/80 bg-pink-50 px-2.5 py-1 text-xs font-semibold text-pink-600 shadow-sm transition-all hover:bg-pink-100 hover:text-pink-700 active:scale-95"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="products.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing {{ products.from }} – {{ products.to }} of {{ products.total }}</p>
                        <div class="flex gap-2">
                            <Link v-if="products.prev_page_url" :href="products.prev_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</Link>
                            <Link v-if="products.next_page_url" :href="products.next_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
