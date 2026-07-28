<script setup>
import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    metrics: Object
});

let pollInterval = null;
onMounted(() => {
    pollInterval = setInterval(() => {
        router.reload({ only: ['metrics'], preserveScroll: true, preserveState: true });
    }, 5000);
});
onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<template>
    <AppLayout title="Supplier Dashboard">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Supplier Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Overview of your activity and requests</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-6 sm:grid-cols-4">
                    <!-- Stat Card 1 -->
                    <div class="glass-card hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Total Orders Received</p>
                                <p class="text-2xl font-bold text-slate-900">{{ metrics.totalOrders }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="glass-card hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Pending POs</p>
                                <p class="text-2xl font-bold text-slate-900">{{ metrics.pendingPos }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="glass-card hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Completed Deliveries</p>
                                <p class="text-2xl font-bold text-slate-900">{{ metrics.completedDeliveries }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 4 -->
                    <div class="glass-card hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Total Sales / Revenue</p>
                                <p class="text-2xl font-bold text-slate-900">${{ parseFloat(metrics.totalSales || 0).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Recent Requests -->
            <div class="glass-card">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Recent Requests</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0012</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Moisturizer</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Qty 50</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Pending</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0013</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Toner</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Qty 20</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Accepted</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700 font-medium">PR-0014</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Serum</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-500">Qty 30</td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">Preparing</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
