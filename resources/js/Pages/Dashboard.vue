<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-card bg-white shadow-card">
                    <div class="p-8 text-center">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.24em] text-accent">
                            SKIMS SHOP
                        </p>

                        <h1 class="mb-3 text-2xl font-bold text-gray-900">
                            Welcome, {{ $page.props.auth?.user?.name }}! 
                        </h1>

                        <p class="mx-auto mb-6 max-w-md leading-relaxed text-muted">
                            You're logged in to your SKIMS SHOP dashboard. Explore premium essentials with confidence.
                        </p>

                        <div class="flex flex-wrap justify-center gap-3">
                            <Link v-if="userRole === 'admin'" :href="route('admin.dashboard')" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                                Open Admin Panel
                            </Link>
                            <Link v-if="userRole === 'supplier'" :href="route('supplier.dashboard')" class="rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white">
                                Open Supplier Panel
                            </Link>
                            <Link v-if="userRole === 'customer'" :href="route('customer.dashboard')" class="rounded-full bg-violet-600 px-4 py-2 text-sm font-semibold text-white">
                                Browse Shop
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
