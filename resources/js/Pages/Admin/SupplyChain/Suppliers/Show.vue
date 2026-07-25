<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

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

                        <table v-else class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr>
                                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">SKU</th>
                                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">MOQ</th>
                                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">Unit Cost</th>
                                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase">On Hand</th>
                                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="sp in supplier.supplierProducts" :key="sp.id" class="py-3">
                                    <td class="py-3 text-sm font-medium text-gray-900">{{ sp.product.name }}</td>
                                    <td class="py-3 text-sm text-gray-500">{{ sp.product.sku }}</td>
                                    <td class="py-3 text-sm text-gray-900">{{ sp.moq }}</td>
                                    <td class="py-3 text-sm text-gray-900">₱{{ Number(sp.unit_cost).toLocaleString() }}</td>
                                    <td class="py-3 text-sm text-gray-900">{{ sp.product.on_hand_qty }}</td>
                                    <td class="py-3 text-right">
                                        <button @click="unlinkProduct(sp.product.id)"
                                                class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 transition">
                                            Unlink
                                        </button>
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
