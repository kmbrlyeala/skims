<script setup>
import { reactive } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    inventory: Object,
    summary: Object,
    filters: Object,
});

const filters = reactive({ search: props.filters?.search ?? '', stock: props.filters?.stock ?? '' });
const applyFilters = () => router.get(route('inventory-manager.supply-inventory.index'), filters, { preserveState: true, replace: true });

// Inline reorder point editing
const editingId = reactive({});
const editForms = reactive({});

const startEdit = (inv) => {
    editingId[inv.id] = true;
    editForms[inv.id] = useForm({ reorder_point: inv.reorder_point });
};

const saveEdit = (inv) => {
    editForms[inv.id].patch(route('inventory-manager.supply-inventory.update', inv.id), {
        onSuccess: () => { delete editingId[inv.id]; },
    });
};

const cancelEdit = (inv) => {
    delete editingId[inv.id];
};

const stockCls = (item) => {
    if (item.is_out_of_stock) return { row: 'bg-red-50/40', badge: 'bg-red-50 text-red-700 border-red-200', label: 'Out of Stock' };
    if (item.is_low_stock)    return { row: '', badge: 'bg-amber-50 text-amber-700 border-amber-200', label: 'Low Stock' };
    return { row: '', badge: 'bg-emerald-50 text-emerald-700 border-emerald-200', label: 'In Stock' };
};
</script>

<template>
    <AppLayout title="Inventory">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory</h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Summary Strip -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total SKUs</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ summary.total_skus }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Low Stock</p>
                        <p class="text-3xl font-bold mt-2" :class="summary.low_stock > 0 ? 'text-amber-600' : 'text-gray-800'">
                            {{ summary.low_stock }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Out of Stock</p>
                        <p class="text-3xl font-bold mt-2" :class="summary.out_of_stock > 0 ? 'text-red-600' : 'text-gray-800'">
                            {{ summary.out_of_stock }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Incoming</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ summary.incoming_total }}</p>
                    </div>
                </div>

                <!-- Info Banner -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-blue-700">
                        <strong>On-hand quantities are read-only</strong> — they update automatically when goods are received via a Purchase Order.
                        You can only edit <strong>Reorder Points</strong> here (click the pencil icon).
                    </p>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3">
                    <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search product or SKU…"
                           class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    <select v-model="filters.stock" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Stock</option>
                        <option value="ok">In Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>

                <!-- Inventory Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">On Hand</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Incoming</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reorder Point</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Updated</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="inventory.data.length === 0">
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No inventory records. Wait for an Admin to create products.
                                </td>
                            </tr>
                            <tr v-for="inv in inventory.data" :key="inv.id"
                                class="hover:bg-gray-50 transition"
                                :class="stockCls(inv).row">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ inv.product_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ inv.sku }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold"
                                          :class="inv.is_out_of_stock ? 'text-red-600' : inv.is_low_stock ? 'text-amber-600' : 'text-gray-900'">
                                        {{ inv.on_hand_qty }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ inv.incoming_qty }}</td>
                                <td class="px-6 py-4">
                                    <div v-if="editingId[inv.id]" class="flex items-center gap-2">
                                        <input v-model="editForms[inv.id].reorder_point" type="number" min="0"
                                               class="w-20 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                                        <button @click="saveEdit(inv)" class="text-xs text-emerald-600 font-medium hover:text-emerald-700">Save</button>
                                        <button @click="cancelEdit(inv)" class="text-xs text-gray-400 hover:text-gray-600">✕</button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <span class="text-sm text-gray-700">{{ inv.reorder_point }}</span>
                                        <button @click="startEdit(inv)" class="text-gray-300 hover:text-accent transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full border"
                                          :class="stockCls(inv).badge">
                                        {{ stockCls(inv).label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">{{ inv.last_updated_at ?? 'Never' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="route('inventory-manager.goods-receipts.create')" class="text-xs text-emerald-600 hover:underline">Receive</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="inventory.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing {{ inventory.from }} – {{ inventory.to }} of {{ inventory.total }}</p>
                        <div class="flex gap-2">
                            <Link v-if="inventory.prev_page_url" :href="inventory.prev_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</Link>
                            <Link v-if="inventory.next_page_url" :href="inventory.next_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
