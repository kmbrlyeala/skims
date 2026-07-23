<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role);
const cartCount = computed(() => page.props.cartCount || 0);
const flash = computed(() => page.props.flash || {});

const showMobileMenu = ref(false);
const showDropdown = ref(false);
const sidebarCollapsed = ref(false);

// Auto-dismiss flash messages
const visibleFlash = ref({ success: null, error: null });
let flashTimer = null;

watch(flash, (val) => {
    if (val.success || val.error) {
        visibleFlash.value = { ...val };
        clearTimeout(flashTimer);
        flashTimer = setTimeout(() => {
            visibleFlash.value = { success: null, error: null };
        }, 3000);
    }
}, { immediate: true });

const dismissFlash = () => {
    visibleFlash.value = { success: null, error: null };
    clearTimeout(flashTimer);
};

const logout = () => {
    router.post(route('logout'));
};

// Role-based navigation items
const navItems = computed(() => {
    switch (userRole.value) {
        case 'admin':
            return [
                { label: 'Dashboard', routeName: 'admin.dashboard', icon: 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z' },
                { label: 'Users', routeName: 'admin.users', icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z' },
                { label: 'Orders', routeName: 'admin.orders', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z' },
                { label: 'Inventory', routeName: 'admin.inventory', icon: 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z M6 6h.008v.008H6V6Z' },
            ];
        case 'supplier':
            return [
                { label: 'Dashboard', routeName: 'supplier.dashboard', icon: 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z' },
                { label: 'Inventory', routeName: 'supplier.inventory', icon: 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z' },
                { label: 'Orders', routeName: 'supplier.orders', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z' },
            ];
        case 'customer':
            return [
                { label: 'Dashboard', routeName: 'customer.dashboard', icon: 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25' },
                { label: 'Shop', routeName: 'customer.shop', icon: 'M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z' },
                { label: 'Cart', routeName: 'customer.cart', icon: 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z', badge: cartCount },
                { label: 'Orders', routeName: 'customer.orders', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z' },
            ];
        default:
            return [];
    }
});

const roleBadge = computed(() => {
    switch (userRole.value) {
        case 'admin': return { label: 'Admin', class: 'bg-slate-800 text-white' };
        case 'supplier': return { label: 'Supplier', class: 'bg-pink-500 text-white' };
        case 'customer': return { label: 'Customer', class: 'bg-violet-500 text-white' };
        default: return { label: 'User', class: 'bg-slate-400 text-white' };
    }
});

const isActive = (routeName) => {
    try { return route().current(routeName); } catch { return false; }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-pink-50/30">
        <Head :title="title" />

        <!-- Flash Messages — auto-dismiss after 3s -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-x-full opacity-0"
            enter-to-class="transform translate-x-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-x-0 opacity-100"
            leave-to-class="transform translate-x-full opacity-0"
        >
            <div v-if="visibleFlash.success" class="fixed right-4 top-4 z-[60] max-w-sm">
                <div class="flash-success flex items-center gap-3 pr-2 shadow-lg">
                    <span>{{ visibleFlash.success }}</span>
                    <button @click="dismissFlash" class="ml-auto rounded-lg p-1 text-emerald-600 hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-x-full opacity-0"
            enter-to-class="transform translate-x-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-x-0 opacity-100"
            leave-to-class="transform translate-x-full opacity-0"
        >
            <div v-if="visibleFlash.error" class="fixed right-4 top-4 z-[60] max-w-sm">
                <div class="flash-error flex items-center gap-3 pr-2 shadow-lg">
                    <span>{{ visibleFlash.error }}</span>
                    <button @click="dismissFlash" class="ml-auto rounded-lg p-1 text-red-600 hover:bg-red-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Mobile Top Bar -->
        <div class="sticky top-0 z-40 flex h-14 items-center justify-between border-b border-slate-100 bg-white/80 px-4 backdrop-blur-xl lg:hidden">
            <button @click="showMobileMenu = true" class="rounded-lg p-2 text-slate-500 hover:bg-slate-50">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <Link :href="route('dashboard')" class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-pink-500 to-violet-500 text-xs font-bold text-white">S</div>
                <span class="text-sm font-bold text-slate-800">SKIMS SHOP</span>
            </Link>
            <div class="w-9" /> <!-- Spacer -->
        </div>

        <!-- Mobile Overlay -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showMobileMenu" @click="showMobileMenu = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden" />
        </Transition>

        <div class="flex">
            <!-- Sidebar -->
            <aside
                :class="[
                    'fixed inset-y-0 left-0 z-50 flex flex-col border-r border-slate-100 bg-white transition-all duration-300 lg:sticky lg:top-0 lg:z-50',
                    showMobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                    sidebarCollapsed ? 'w-[68px]' : 'w-64',
                ]"
                style="height: 100vh; height: 100dvh;"
            >
                <!-- Logo -->
                <div class="flex h-16 items-center justify-between border-b border-slate-100 px-4">
                    <Link :href="route('dashboard')" class="flex items-center gap-2.5 overflow-hidden">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-violet-500 text-sm font-bold text-white shadow-sm">
                            S
                        </div>
                        <span v-if="!sidebarCollapsed" class="text-sm font-bold tracking-wide text-slate-800">SKIMS SHOP</span>
                    </Link>
                    <!-- Collapse toggle (desktop only) -->
                    <button
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        class="hidden rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-600 lg:block"
                    >
                        <svg class="h-4 w-4 transition-transform" :class="sidebarCollapsed && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <!-- Close (mobile only) -->
                    <button @click="showMobileMenu = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-50 lg:hidden">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Role badge -->
                <div v-if="!sidebarCollapsed" class="px-4 pt-4 pb-2">
                    <span :class="roleBadge.class" class="inline-block rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                        {{ roleBadge.label }}
                    </span>
                </div>

                <!-- Nav Links -->
                <nav class="flex-1 overflow-y-auto px-3 py-2">
                    <div class="space-y-1">
                        <Link
                            v-for="item in navItems"
                            :key="item.routeName"
                            :href="route(item.routeName)"
                            @click="showMobileMenu = false"
                            class="relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150"
                            :class="isActive(item.routeName)
                                ? 'bg-gradient-to-r from-pink-50 to-rose-50 text-pink-700 shadow-sm'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            :title="sidebarCollapsed ? item.label : undefined"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            <span v-if="!sidebarCollapsed">{{ item.label }}</span>
                            <!-- Cart badge -->
                            <span
                                v-if="item.badge && item.badge.value > 0"
                                class="ml-auto flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-pink-500 px-1.5 text-[10px] font-bold text-white"
                                :class="sidebarCollapsed && 'absolute -right-0.5 -top-0.5 ml-0'"
                            >
                                {{ item.badge.value }}
                            </span>
                        </Link>
                    </div>
                </nav>

                <!-- User section at bottom -->
                <div class="border-t border-slate-100 p-3">
                    <div class="relative">
                        <button
                            @click="showDropdown = !showDropdown"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left transition-colors hover:bg-slate-50"
                        >
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-pink-400 to-violet-400 text-xs font-bold text-white">
                                {{ user?.name?.charAt(0)?.toUpperCase() }}
                            </div>
                            <div v-if="!sidebarCollapsed" class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-700">{{ user?.name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ user?.email }}</p>
                            </div>
                            <svg v-if="!sidebarCollapsed" class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <Transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <div
                                v-if="showDropdown"
                                class="absolute bottom-full left-0 mb-2 w-48 origin-bottom-left rounded-xl border border-slate-100 bg-white py-1 shadow-lg"
                            >
                                <Link
                                    :href="route('profile.show')"
                                    @click="showDropdown = false"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 transition-colors hover:bg-slate-50"
                                >
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Profile
                                </Link>
                                <div class="my-1 border-t border-slate-100" />
                                <button
                                    @click="logout"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-red-600 transition-colors hover:bg-red-50"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Log Out
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 lg:min-h-screen">
                <!-- Page Heading -->
                <header v-if="$slots.header" class="border-b border-slate-100 bg-white/60 backdrop-blur-sm">
                    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>

    <!-- Click outside to close dropdown -->
    <div v-if="showDropdown" @click="showDropdown = false" class="fixed inset-0 z-40" />
</template>
