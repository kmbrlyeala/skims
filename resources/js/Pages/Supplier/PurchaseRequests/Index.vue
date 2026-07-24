<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
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
    blue:   'bg-blue-50 text-blue-700',
    red:    'bg-red-50 text-red-700',
    green:  'bg-emerald-50 text-emerald-700',
    orange: 'bg-orange-50 text-orange-700',
}[color] || 'bg-gray-100 text-gray-600');
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="purchaseRequests.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-base text-slate-400 font-medium">
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
    </AppLayout>
</template>
