<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    notifications: Array
});

const activeTab = ref('all');

const filteredNotifications = computed(() => {
    if (activeTab.value === 'unread') {
        return props.notifications.filter(n => !n.read);
    }
    return props.notifications;
});

const markAllRead = () => {
    router.post(route('supplier.notifications.mark-read'), {}, {
        preserveScroll: true
    });
};

const iconForType = (type) => {
    switch(type) {
        case 'PO_Received': return 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z';
        case 'Discrepancy': return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
        case 'Delivery_Complete': return 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        default: return 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0';
    }
};

const colorForType = (type) => {
    switch(type) {
        case 'PO_Received': return 'bg-blue-100 text-blue-600';
        case 'Discrepancy': return 'bg-red-100 text-red-600';
        case 'Delivery_Complete': return 'bg-emerald-100 text-emerald-600';
        default: return 'bg-slate-100 text-slate-600';
    }
};
</script>

<template>
    <AppLayout title="Notifications">
        <div class="page-container max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500">Real-time alerts and sync updates</p>
                </div>
                <button @click="markAllRead" class="btn-secondary">Mark All as Read</button>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4">
                <div v-for="notif in notifications" :key="notif.id" :class="['flex items-start gap-4 rounded-2xl p-4 transition-all duration-200 border', notif.read ? 'bg-white border-slate-100 shadow-sm' : 'bg-blue-50/50 border-blue-100 shadow-md ring-1 ring-blue-500/10']">
                    <div class="flex-shrink-0">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full" :class="colorForType(notif.type)">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="iconForType(notif.type)" />
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1 pt-0.5">
                        <div class="flex items-center justify-between gap-x-2">
                            <h3 class="text-sm font-bold text-slate-900">{{ notif.title }}</h3>
                            <span class="text-xs text-slate-500 whitespace-nowrap">{{ notif.date }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">{{ notif.message }}</p>
                    </div>
                    <div v-if="!notif.read" class="flex-shrink-0 mt-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                    </div>
                </div>
                
                <div v-if="notifications.length === 0" class="rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">No new notifications</h3>
                    <p class="mt-1 text-sm text-slate-500">You're all caught up!</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
