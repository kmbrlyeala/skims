<script setup>
import { reactive, ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    purchaseRequests: Object,
    filters: Object,
});

const filters = reactive({ status: '', search: '' });
const applyFilters = () => router.get(route('supplier.purchase-requests.index'), filters, { preserveState: true, replace: true });

const statusBadge = (color) => ({
    gray:   'bg-gray-100 text-gray-600',
    amber:  'bg-amber-50 text-amber-700',
    purple: 'bg-purple-50 text-purple-700',
    blue:   'bg-blue-50 text-blue-700',
    red:    'bg-red-50 text-red-700',
    green:  'bg-emerald-50 text-emerald-700',
    orange: 'bg-orange-50 text-orange-700',
}[color] || 'bg-gray-100 text-gray-600');

// Modal logic
const approvingPr = ref(null);
const approveForm = useForm({
    expected_delivery_date: '',
});

const openApproveModal = (pr) => {
    approvingPr.value = pr;
    // Default to a date slightly in the future
    const defaultDate = new Date();
    defaultDate.setDate(defaultDate.getDate() + 14);
    approveForm.expected_delivery_date = defaultDate.toISOString().split('T')[0];
};

const closeApproveModal = () => {
    approvingPr.value = null;
    approveForm.reset();
};

const submitApproval = () => {
    approveForm.post(route('supplier.purchase-requests.approve', approvingPr.value.id), {
        onSuccess: () => closeApproveModal(),
    });
};
</script>

<template>
    <AppLayout title="Incoming Purchase Requests">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Incoming Purchase Requests</h1>
                    <p class="mt-1 text-sm text-slate-500">View items requested for manufacturing</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="glass-card !p-4 flex flex-wrap gap-4">
                <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search product…" class="form-input max-w-xs" />
                <select v-model="filters.status" @change="applyFilters" class="form-select max-w-xs">
                    <option value="">All Statuses</option>
                    <option value="pending_approval">Pending Admin Approval</option>
                    <option value="pending_factory_approval">Pending Factory Approval</option>
                    <option value="approved">Approved & Ordered</option>
                    <option value="rejected">Rejected</option>
                    <option value="received">Received</option>
                    <option value="partially_received">Partially Received</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/80 shadow-lg backdrop-blur-md">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>PR #</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="purchaseRequests.data.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-base text-slate-400 font-medium">
                                    No purchase requests found.
                                </td>
                            </tr>
                            <tr v-for="pr in purchaseRequests.data" :key="pr.id">
                                <td class="font-bold text-slate-600">
                                    #{{ pr.id }}
                                </td>
                                <td>
                                    <p class="text-base font-bold text-slate-900">{{ pr.product.name }}</p>
                                    <p class="text-xs font-mono text-slate-500">{{ pr.product.sku }}</p>
                                </td>
                                <td class="font-bold text-slate-900">{{ pr.quantity_requested }}</td>
                                <td class="font-bold text-emerald-600">₱{{ Number(pr.total_cost).toLocaleString() }}</td>
                                <td>
                                    <span class="badge" :class="statusBadge(pr.status_color)">
                                        {{ pr.status_label }}
                                    </span>
                                    <span v-if="pr.po_number" class="block mt-1 text-xs font-mono text-violet-600 font-bold">{{ pr.po_number }}</span>
                                </td>
                                <td class="text-slate-600 font-medium">{{ pr.requester }}</td>
                                <td class="text-right">
                                    <button 
                                        v-if="pr.status === 'pending_factory_approval'"
                                        @click="openApproveModal(pr)"
                                        class="btn-primary !py-1.5 !px-3 !text-xs"
                                    >
                                        Approve & Accept
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="purchaseRequests.last_page > 1" class="border-t border-slate-100 px-6 py-4 flex items-center justify-between bg-white/50">
                    <p class="text-sm font-medium text-slate-500">Showing {{ purchaseRequests.from }} – {{ purchaseRequests.to }} of {{ purchaseRequests.total }}</p>
                    <div class="flex gap-2">
                        <Link v-if="purchaseRequests.prev_page_url" :href="purchaseRequests.prev_page_url" class="btn-secondary !px-4 !py-2 !text-sm">← Prev</Link>
                        <Link v-if="purchaseRequests.next_page_url" :href="purchaseRequests.next_page_url" class="btn-secondary !px-4 !py-2 !text-sm">Next →</Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Modal -->
        <div v-if="approvingPr" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Approve Purchase Request #{{ approvingPr.id }}</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        You are accepting the order for <strong>{{ approvingPr.quantity_requested }}x {{ approvingPr.product.name }}</strong>. 
                        Please provide your estimated delivery date.
                    </p>

                    <form @submit.prevent="submitApproval" class="space-y-4">
                        <div>
                            <label class="form-label">Expected Delivery Date</label>
                            <input 
                                type="date" 
                                v-model="approveForm.expected_delivery_date" 
                                class="form-input w-full"
                                required
                            />
                            <p v-if="approveForm.errors.expected_delivery_date" class="mt-1 text-xs text-red-600">
                                {{ approveForm.errors.expected_delivery_date }}
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="closeApproveModal" class="btn-secondary w-full" :disabled="approveForm.processing">Cancel</button>
                            <button type="submit" class="btn-primary w-full" :disabled="approveForm.processing">
                                Confirm & Accept
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
