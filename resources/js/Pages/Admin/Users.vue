<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

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
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Change Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td class="font-medium text-slate-900">{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold uppercase" :class="roleBadgeClass(user.role)">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="text-slate-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                                <td>
                                    <select
                                        :value="user.role"
                                        @change="updateRole(user, $event.target.value)"
                                        class="form-select w-32 py-1.5 text-xs"
                                    >
                                        <option value="admin">Admin</option>
                                        <option value="supplier">Supplier</option>
                                        <option value="customer">Customer</option>
                                    </select>
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
