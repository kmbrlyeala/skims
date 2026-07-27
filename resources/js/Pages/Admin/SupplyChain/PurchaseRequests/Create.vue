<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    products: Array,
    suppliers: Array,
    prefill: Object,
    routePrefix: {
        type: String,
        default: 'admin',
    },
});

const form = useForm({
    supplier_id: props.prefill?.supplier_id ?? '',
    product_id: props.prefill?.product_id ?? '',
    quantity_requested: props.prefill?.quantity ?? '',
    unit_cost: props.prefill?.unit_cost ?? '',
    expected_delivery_date: '',
    notes: '',
    draft_pr_id: props.prefill?.draft_pr_id ?? '',
});

// Auto-fill product details when factory + product are selected
const selectedFactory = computed(() => props.suppliers.find(s => s.id == form.supplier_id));

// Filter products based on selected factory
const availableProducts = computed(() => {
    if (!selectedFactory.value) return [];
    // The controller currently returns products with their suppliers (factories).
    // We need to find products that are linked to the selected factory.
    return props.products.filter(p => p.suppliers.some(s => s.id == form.supplier_id));
});

const selectedSupplierData = computed(() => {
    if (!form.product_id || !form.supplier_id) return null;
    const product = props.products.find(p => p.id == form.product_id);
    return product?.suppliers?.find(s => s.id == form.supplier_id) ?? null;
});

// Auto-fill unit_cost when product selected
watch(selectedSupplierData, (sd) => {
    if (sd && !props.prefill?.unit_cost) {
        form.unit_cost = sd.unit_cost;
    }
});

// If the factory changes, clear the product selection if it's no longer valid
watch(() => form.supplier_id, (newFactoryId) => {
    if (newFactoryId) {
        const productStillValid = availableProducts.value.some(p => p.id == form.product_id);
        if (!productStillValid) {
            form.product_id = '';
            form.unit_cost = '';
        }
    }
});

const moqWarning = computed(() => {
    if (!selectedSupplierData.value || !form.quantity_requested) return null;
    const qty = parseInt(form.quantity_requested);
    const moq = selectedSupplierData.value.moq;
    if (qty < moq) return `Warning: quantity ${qty} is below the factory's MOQ of ${moq}. The approver will need to override this.`;
    return null;
});

const submit = () => {
    form.post(route(`${props.routePrefix}.purchase-requests.store`));
};
</script>

<template>
    <AppLayout title="New Purchase Request">
        <div class="page-container max-w-3xl space-y-6">
            <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
                <Link :href="route(`${routePrefix}.purchase-requests.index`)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                        New Purchase Request
                        <span v-if="prefill" class="badge badge-active text-xs">
                            From Reorder Alert
                        </span>
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">Create a new purchase request for factories</p>
                </div>
            </div>

            <div class="glass-card !p-8">
                <form @submit.prevent="submit" class="space-y-8">

                    <!-- Factory & Product -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">Factory & Product</h3>

                        <div>
                            <label class="form-label">Factory *</label>
                            <select v-model="form.supplier_id" required class="form-select">
                                <option value="" disabled>Select a factory…</option>
                                <option v-for="s in suppliers" :key="s.id" :value="s.id">
                                    {{ s.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.supplier_id" class="text-sm font-bold text-red-600 mt-2">{{ form.errors.supplier_id }}</p>
                        </div>

                        <div>
                            <label class="form-label">Product / SKU *</label>
                            <select v-model="form.product_id" required class="form-select" :disabled="!form.supplier_id">
                                <option value="" disabled>{{ form.supplier_id ? 'Select a product…' : 'Select a factory first' }}</option>
                                <option v-for="p in availableProducts" :key="p.id" :value="p.id">
                                    {{ p.name }} ({{ p.sku }})
                                </option>
                            </select>
                            <p v-if="form.errors.product_id" class="text-sm font-bold text-red-600 mt-2">{{ form.errors.product_id }}</p>
                        </div>

                        <!-- Factory details box -->
                        <div v-if="selectedSupplierData" class="rounded-xl border border-blue-200 bg-blue-50/50 p-5 grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs font-bold text-blue-500 uppercase tracking-widest">MOQ</p>
                                <p class="text-base font-bold text-blue-900 mt-1">{{ selectedSupplierData.moq }} units</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-500 uppercase tracking-widest">Mfg Cost</p>
                                <p class="text-base font-bold text-blue-900 mt-1">₱{{ selectedSupplierData.unit_cost }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-500 uppercase tracking-widest">Lead Time</p>
                                <p class="text-base font-bold text-blue-900 mt-1">{{ selectedSupplierData.lead_time_days }} days</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="space-y-6 pt-6">
                        <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">Order Details</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Quantity *</label>
                                <input v-model="form.quantity_requested" type="number" min="1" required class="form-input" />
                                <p v-if="form.errors.quantity_requested" class="text-sm font-bold text-red-600 mt-2">{{ form.errors.quantity_requested }}</p>
                            </div>
                            <div>
                                <label class="form-label">Manufacturing Unit Cost (₱) *</label>
                                <input v-model="form.unit_cost" type="number" step="0.01" min="0" required readonly class="form-input bg-slate-100 cursor-not-allowed opacity-80" />
                                <p class="text-xs text-slate-500 mt-1 mt-1 font-medium italic">Fixed by factory agreement.</p>
                            </div>
                        </div>

                        <!-- MOQ Warning -->
                        <div v-if="moqWarning" class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm font-bold text-amber-800">{{ moqWarning }}</p>
                        </div>

                        <!-- Total preview -->
                        <div v-if="form.quantity_requested && form.unit_cost" class="rounded-xl bg-slate-100/50 p-4 text-right">
                            <span class="text-sm font-medium text-slate-500 uppercase tracking-wider mr-3">Estimated Total Cost</span>
                            <span class="text-2xl font-black text-emerald-600">
                                ₱{{ (Number(form.quantity_requested) * Number(form.unit_cost)).toLocaleString() }}
                            </span>
                        </div>

                        <div>
                            <label class="form-label">Notes for Factory</label>
                            <textarea v-model="form.notes" rows="3" placeholder="Any special manufacturing instructions…" class="form-input"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                        <Link :href="route(`${routePrefix}.purchase-requests.index`)" class="btn-secondary flex-1 max-w-[200px]">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing" class="btn-primary flex-1">
                            {{ form.processing ? 'Submitting…' : 'Submit for Approval' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
