<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const expandedRows = ref(new Set());
const toggleRow = (id) => {
    const newSet = new Set(expandedRows.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedRows.value = newSet;
};


const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');

const applyFilters = () => {
    router.get(route('admin.users'), {
        search: search.value || undefined,
        role: roleFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const updateRole = (user, newRole) => {
    router.put(route('admin.users.updateRole', user.id), {
        role: newRole,
    }, { preserveState: true });
};

const roleBadgeClass = (role) => ({
    admin: 'bg-slate-800 text-white',
    supplier: 'bg-pink-100 text-pink-700',
    customer: 'bg-violet-100 text-violet-700',
}[role] || 'bg-slate-100 text-slate-600');
</script>

<template>
    <AppLayout title="Manage Users">
        <div class="page-container space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Users</h1>
                <p class="mt-1 text-sm text-slate-500">Manage all platform users and their roles</p>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <input
                    v-model="search"
                    @input="applyFilters"
                    type="text"
                    placeholder="Search by name or email..."
                    class="form-input max-w-xs"
                />
                <select v-model="roleFilter" @change="applyFilters" class="form-select w-40">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="supplier">Supplier</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <!-- Users Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 block md:table">
                        <thead class="hidden md:table-header-group bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="py-4 px-4 font-bold">Name</th>
                                <th class="py-4 px-4 font-bold">Email</th>
                                <th class="py-4 px-4 font-bold">Role</th>
                                <th class="py-4 px-4 font-bold">Joined</th>
                                <th class="py-4 px-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-4 md:p-0 md:divide-y md:divide-slate-100">
                            <tr v-for="user in users.data" :key="user.id" class="flex flex-col md:table-row bg-white rounded-xl shadow-sm border border-slate-200 md:border-0 md:rounded-none md:shadow-none transition-colors hover:bg-slate-50/50">
                                <td class="py-3 px-4 flex justify-between items-center md:table-cell border-b border-slate-100 md:border-0">
                                    <span class="font-medium text-slate-900">{{ user.name }}</span>
                                    <button @click="toggleRow(user.id)" class="md:hidden p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-lg shrink-0 ml-4">
                                        <svg class="w-5 h-5 transition-transform" :class="expandedRows.has(user.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(user.id), 'flex md:table-cell justify-between items-center': expandedRows.has(user.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Email</span>
                                    <span>{{ user.email }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(user.id), 'flex md:table-cell justify-between items-center': expandedRows.has(user.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Role</span>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold uppercase" :class="roleBadgeClass(user.role)">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-slate-50 md:border-0" :class="{'hidden md:table-cell': !expandedRows.has(user.id), 'flex md:table-cell justify-between items-center': expandedRows.has(user.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Joined</span>
                                    <span class="text-slate-500">{{ new Date(user.created_at).toLocaleDateString() }}</span>
                                </td>
                                <td class="py-3 px-4 md:text-center" :class="{'hidden md:table-cell': !expandedRows.has(user.id), 'flex md:table-cell justify-between items-center': expandedRows.has(user.id)}">
                                    <span class="md:hidden text-xs font-bold text-slate-400 uppercase">Actions</span>
                                    <div class="flex justify-end md:justify-center items-center w-full md:w-auto">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button class="p-2 md:p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                                    <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <template #content>
                                                <div class="px-4 py-2 text-xs text-slate-500">Change Role:</div>
                                                <DropdownLink as="button" @click="updateRole(user, 'admin')">Set as Admin</DropdownLink>
                                                <DropdownLink as="button" @click="updateRole(user, 'supplier')">Set as Supplier</DropdownLink>
                                                <DropdownLink as="button" @click="updateRole(user, 'customer')">Set as Customer</DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.last_page > 1" class="flex items-center justify-between border-t border-slate-100 px-4 py-3">
                    <p class="text-xs text-slate-500">
                        Showing {{ users.from }}–{{ users.to }} of {{ users.total }}
                    </p>
                    <div class="flex gap-1">
                        <a
                            v-for="link in users.links"
                            :key="link.label"
                            :href="link.url"
                            @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                            :class="link.active ? 'bg-pink-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
