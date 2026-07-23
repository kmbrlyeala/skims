<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    orders: Object,
});

const statusClass = (status) => ({
    pending: 'badge-pending',
    processing: 'badge-processing',
    shipped: 'badge-shipped',
    delivered: 'badge-delivered',
    cancelled: 'badge-cancelled',
}[status] || 'badge-draft');
</script>

<template>
    <AppLayout title="My Orders">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">My Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Track your beauty hauls</p>
            </div>

            <div v-if="orders.data?.length" class="space-y-3">
                <Link
                    v-for="order in orders.data"
                    :key="order.id"
                    :href="route('customer.orders.show', order.id)"
                    class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-center gap-4">
                        <!-- Item thumbnails -->
                        <div class="flex -space-x-2">
                            <template v-for="(item, i) in order.items?.slice(0, 3)" :key="item.id">
                                <div class="h-10 w-10 overflow-hidden rounded-lg border-2 border-white bg-slate-100">
                                    <img
                                        v-if="item.inventory_item?.image_url"
                                        :src="item.inventory_item.image_url"
                                        :alt="item.inventory_item?.name"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full items-center justify-center text-xs text-slate-300">✦</div>
                                </div>
                            </template>
                            <div v-if="order.items?.length > 3" class="flex h-10 w-10 items-center justify-center rounded-lg border-2 border-white bg-slate-100 text-xs font-bold text-slate-500">
                                +{{ order.items.length - 3 }}
                            </div>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-900">Order #{{ order.id }}</p>
                            <p class="text-xs text-slate-500">
                                {{ order.items?.length }} item(s) · {{ new Date(order.created_at).toLocaleDateString() }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="font-bold text-slate-900">₱{{ Number(order.total).toFixed(2) }}</p>
                        <span class="badge" :class="statusClass(order.status)">{{ order.status }}</span>
                    </div>
                </Link>
            </div>

            <!-- Empty -->
            <div v-else class="py-16 text-center">
                <p class="text-4xl">📦</p>
                <p class="mt-3 text-lg font-medium text-slate-500">No orders yet</p>
                <Link :href="route('customer.shop')" class="btn-primary mt-4 inline-flex">
                    Start Shopping
                </Link>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="flex justify-center gap-1">
                <a
                    v-for="link in orders.links"
                    :key="link.label"
                    :href="link.url"
                    @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                    class="rounded-lg px-3.5 py-2 text-sm font-medium transition-colors"
                    :class="link.active ? 'bg-pink-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
