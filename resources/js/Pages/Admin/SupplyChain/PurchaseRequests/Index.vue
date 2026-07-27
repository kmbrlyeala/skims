<script setup>
import { reactive } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    purchaseRequests: Object,
    filters: Object,
    isManager: Boolean,
    routePrefix: {
        type: String,
        default: 'admin',
    },
});

const filters = reactive({ status: '', search: '' });
const applyFilters = () => router.get(route(`${routePrefix}.purchase-requests.index`), filters, { preserveState: true, replace: true });

const approve = (pr, override = false) => {
    if (!confirm(`Approve PR #${pr.id} for ${pr.quantity_requested} × ${pr.product.name}?`)) return;
    router.post(route(`${routePrefix}.purchase-requests.approve`, pr.id), { override_moq: override });
};

const reject = (pr) => {
    if (!confirm(`Reject PR #${pr.id}?`)) return;
    router.post(route(`${routePrefix}.purchase-requests.reject`, pr.id));
};

const statusBadge = (color) => ({
    gray:   'bg-gray-100 text-gray-600',
    amber:  'bg-amber-50 text-amber-700',
    purple: 'bg-purple-50 text-purple-700',
    blue:   'bg-blue-50 text-blue-700',
    red:    'bg-red-50 text-red-700',
    green:  'bg-emerald-50 text-emerald-700',
    orange: 'bg-orange-50 text-orange-700',
}[color] || 'bg-gray-100 text-gray-600');
</script>

<template>
    <AppLayout title="Purchase Requests">
        <div class="page-container space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage supply requests and approvals</p>
                </div>
                <Link :href="route(`${routePrefix}.purchase-requests.create`)" class="btn-primary btn-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New PR
                </Link>
            </div>

            <!-- Filters -->
            <div class="glass-card !p-4 flex flex-wrap gap-4">
                <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search product…" class="form-input max-w-xs" />
                <select v-model="filters.status" @change="applyFilters" class="form-select max-w-xs">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="pending_approval">Pending Admin Approval</option>
                    <option value="pending_factory_approval">Pending Factory Approval</option>
                    <option value="approved">Approved</option>
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
                                <th>Supplier</th>
                                <th>Qty</th>
                                <th>Total Cost</th>
                                <th>Expected</th>
                                <th>Status</th>
                                <th>By</th>
                                <th v-if="isManager" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="purchaseRequests.data.length === 0">
                                <td :colspan="isManager ? 9 : 8" class="px-6 py-12 text-center">
                                    <div class="mx-auto max-w-sm space-y-3">
                                        <p class="text-sm font-medium text-slate-400">No purchase requests found.</p>
                                        <div>
                                            <Link
                                                :href="route(`${routePrefix}.purchase-requests.create`)"
                                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:shadow-md hover:brightness-105 active:scale-95"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Create Purchase Request
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="pr in purchaseRequests.data" :key="pr.id" :class="pr.is_auto_draft ? 'bg-amber-50/50' : ''">
                                <td class="font-bold text-slate-600">
                                    #{{ pr.id }}
                                    <span v-if="pr.is_auto_draft" class="ml-1 text-xs text-amber-600 font-bold uppercase tracking-wider">(Auto)</span>
                                </td>
                                <td>
                                    <p class="text-base font-bold text-slate-900">{{ pr.product.name }}</p>
                                    <p class="text-xs font-mono text-slate-500">{{ pr.product.sku }}</p>
                                </td>
                                <td class="font-medium text-slate-700">{{ pr.supplier.name }}</td>
                                <td class="font-bold text-slate-900">{{ pr.quantity_requested }}</td>
                                <td class="font-bold text-emerald-600">₱{{ Number(pr.total_cost).toLocaleString() }}</td>
                                <td class="text-slate-500 font-medium">{{ pr.expected_delivery_date ?? '—' }}</td>
                                <td>
                                    <span class="badge" :class="statusBadge(pr.status_color)">
                                        {{ pr.status_label }}
                                    </span>
                                    <span v-if="pr.po_number" class="block mt-1 text-xs font-mono text-violet-600 font-bold">{{ pr.po_number }}</span>
                                </td>
                                <td class="text-slate-600 font-medium">{{ pr.requester }}</td>
                                <td v-if="isManager" class="text-right">
                                    <div v-if="pr.status === 'pending_approval'" class="flex items-center justify-end gap-2">
                                        <button @click="approve(pr)" class="btn-primary !px-3 !py-2 !text-xs">
                                            Approve
                                        </button>
                                        <button @click="reject(pr)" class="btn-danger !px-3 !py-2 !text-xs">
                                            Reject
                                        </button>
                                    </div>
                                    <span v-else class="text-sm font-bold text-slate-300">—</span>
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
    </AppLayout>
</template>
