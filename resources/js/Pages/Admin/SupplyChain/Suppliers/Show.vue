<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedRows.value = newSet;
};


const props = defineProps({
    supplier: Object,
    products: Array,
});

const showLinkModal = ref(false);
const linkForm = useForm({
    product_id: '',
    moq: 1,
    unit_cost: 0,
});

const submitLink = () => {
    linkForm.post(route('admin.suppliers.link-product', props.supplier.id), {
        onSuccess: () => { showLinkModal.value = false; linkForm.reset(); },
    });
};

const unlinkProduct = (productId) => {
    if (confirm('Remove this product from supplier?')) {
        linkForm.delete(route('admin.suppliers.unlink-product', [props.supplier.id, productId]));
    }
};

const platformLabel = (p) => ({ alibaba: 'Alibaba', local_factory: 'Local Supplier', other: 'Other' }[p] || p);
const statusColor = (s) => ({
    pending_approval: 'bg-amber-50 text-amber-700',
    approved: 'bg-blue-50 text-blue-700',
    received: 'bg-emerald-50 text-emerald-700',
    rejected: 'bg-red-50 text-red-700',
    draft: 'bg-gray-100 text-gray-600',
    partially_received: 'bg-orange-50 text-orange-700',
}[s] || 'bg-gray-100 text-gray-600');
</script>

<template>
    <AppLayout :title="supplier.name">
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.suppliers.index')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ supplier.name }}</h2>
                <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                      :class="supplier.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                    {{ supplier.is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Supplier Profile -->
                <div class="grid lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
                        <h3 class="font-semibold text-gray-900 mb-4">Supplier Details</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Platform</dt>
                                <dd class="mt-1">
                                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">
                                        {{ supplier.source_platform_label }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Lead Time</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ supplier.lead_time_days }} days</dd>
                            </div>
                            <div v-if="supplier.contact_name">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Contact</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ supplier.contact_name }}</dd>
                            </div>
                            <div v-if="supplier.contact_email">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                                <dd class="text-sm text-gray-700">{{ supplier.contact_email }}</dd>
                            </div>
                            <div v-if="supplier.contact_phone">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Phone</dt>
                                <dd class="text-sm text-gray-700">{{ supplier.contact_phone }}</dd>
                            </div>
                            <div v-if="supplier.notes">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Notes / Terms</dt>
                                <dd class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">{{ supplier.notes }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Products linked to this supplier -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900">Products & MOQ</h3>
                            <button @click="showLinkModal = true"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium bg-accent text-white rounded-lg hover:bg-opacity-90 transition">
                                + Link Product
                            </button>
                        </div>

                        <div v-if="supplier.supplierProducts.length === 0" class="text-center py-8 text-gray-400 text-sm">
                            No products linked. <button @click="showLinkModal = true" class="text-accent underline">Link a product.</button>
                        </div>

                        <table v-else class="w-full text-left text-sm text-slate-600 block md:table">
                            <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="py-4 px-4 font-bold">Product</th>
                                    <th class="py-4 px-4 font-bold">SKU</th>
                                    <th class="py-4 px-4 font-bold">MOQ</th>
                                    <th class="py-4 px-4 font-bold">Unit Cost</th>
                                    <th class="py-4 px-4 font-bold">On Hand</th>
                                    <th class="py-4 px-4 font-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                                <tr v-for="sp in supplier.supplierProducts" :key="sp.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                    <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                        <span class="font-medium text-slate-900">{{ sp.product.name }}</span>
                                        <button @click="toggleRow(sp.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                            <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(sp.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(sp.id), 'flex md:table-cell justify-between items-center': expandedRows.has(sp.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">SKU</span>
                                        <span>{{ sp.product.sku }}</span>
                                    </td>
                                    <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(sp.id), 'flex md:table-cell justify-between items-center': expandedRows.has(sp.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">MOQ</span>
                                        <span>{{ sp.moq }}</span>
                                    </td>
                                    <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(sp.id), 'flex md:table-cell justify-between items-center': expandedRows.has(sp.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Unit Cost</span>
                                        <span>₱{{ Number(sp.unit_cost).toLocaleString() }}</span>
                                    </td>
                                    <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(sp.id), 'flex md:table-cell justify-between items-center': expandedRows.has(sp.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">On Hand</span>
                                        <span>{{ sp.product.on_hand_qty }}</span>
                                    </td>
                                    <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(sp.id), 'flex md:table-cell justify-between items-center': expandedRows.has(sp.id)}">
                                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                        <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                            <Dropdown align="right" width="48">
                                                <template #trigger>
                                                    <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                        <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                <template #content>
                                                    <DropdownLink as="button" @click="unlinkProduct(sp.product.id)" class="!text-red-600 hover:!bg-red-50">
                                                        Unlink
                                                    </DropdownLink>
                                                </template>
                                            </Dropdown>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Purchase Requests -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900">Recent Purchase Requests</h3>
                        <Link :href="route('admin.purchase-requests.create')" class="text-xs text-accent hover:underline">New PR →</Link>
                    </div>
                    <div v-if="supplier.recent_prs.length === 0" class="text-center py-8 text-gray-400 text-sm">
                        No purchase requests for this supplier yet.
                    </div>
                    <div v-else class="space-y-2">
                        <div v-for="pr in supplier.recent_prs" :key="pr.id"
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ pr.product }}</p>
                                <p class="text-xs text-gray-500">{{ pr.quantity }} units · {{ pr.created_at }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full" :class="statusColor(pr.status)">
                                {{ pr.status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link Product Modal -->
        <div v-if="showLinkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-lg text-gray-900">Link Product to Supplier</h3>
                    <button @click="showLinkModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitLink" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                        <select v-model="linkForm.product_id" required
                                class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                            <option value="" disabled>Select a product…</option>
                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                        </select>
                        <p v-if="linkForm.errors.product_id" class="text-xs text-red-600 mt-1">{{ linkForm.errors.product_id }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">MOQ *</label>
                            <input v-model="linkForm.moq" type="number" min="1" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost *</label>
                            <input v-model="linkForm.unit_cost" type="number" step="0.01" min="0" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="linkForm.processing"
                                class="flex-1 py-2.5 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition disabled:opacity-50">
                            {{ linkForm.processing ? 'Linking…' : 'Link Product' }}
                        </button>
                        <button type="button" @click="showLinkModal = false"
                                class="px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
