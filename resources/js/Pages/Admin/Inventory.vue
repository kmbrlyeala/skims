<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    items: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get(route('admin.inventory'), {
        search: search.value || undefined,
    }, { preserveState: true, replace: true });
};

const statusClass = (status) => ({
    active: 'badge-active',
    draft: 'badge-draft',
    hidden: 'badge-hidden',
}[status] || 'badge-draft');
</script>

<template>
    <AppLayout title="All Inventory">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Inventory</h1>
                <p class="mt-1 text-sm text-slate-500">All products across all suppliers</p>
            </div>

            <div>
                <input
                    v-model="search"
                    @input="applyFilters"
                    type="text"
                    placeholder="Search by name or SKU..."
                    class="form-input max-w-xs"
                />
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Supplier</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items.data" :key="item.id">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img
                                            v-if="item.image_url"
                                            :src="item.image_url"
                                            :alt="item.name"
                                            class="h-9 w-9 rounded-lg object-cover"
                                        />
                                        <div v-else class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">—</div>
                                        <span class="font-medium text-slate-900">{{ item.name }}</span>
                                    </div>
                                </td>
                                <td>{{ item.supplier?.name }}</td>
                                <td class="font-mono text-xs text-slate-500">{{ item.sku }}</td>
                                <td>
                                    <span :class="item.stock < 10 ? 'text-red-600 font-semibold' : 'text-slate-700'">{{ item.stock }}</span>
                                </td>
                                <td class="font-semibold">₱{{ Number(item.price).toFixed(2) }}</td>
                                <td><span class="badge" :class="statusClass(item.status)">{{ item.status }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="items.last_page > 1" class="flex items-center justify-between border-t border-slate-100 px-4 py-3">
                    <p class="text-xs text-slate-500">Showing {{ items.from }}–{{ items.to }} of {{ items.total }}</p>
                    <div class="flex gap-1">
                        <a
                            v-for="link in items.links"
                            :key="link.label"
                            :href="link.url"
                            @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                            :class="link.active ? 'bg-pink-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
