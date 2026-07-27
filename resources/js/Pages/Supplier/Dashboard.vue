<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Supplier Dashboard">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Supplier Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Overview of your activity and requests</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-6 sm:grid-cols-3">
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wide">Pending PRs</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-pink-600">4</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wide">Active POs</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-pink-600">2</dd>
                </div>
                <div class="glass-card flex flex-col items-center justify-center text-center p-6">
                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wide">Deliveries</dt>
                    <dd class="mt-2 text-4xl font-extrabold text-pink-600">18</dd>
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
