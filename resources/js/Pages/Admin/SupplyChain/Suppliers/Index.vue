<script setup>
import { ref, reactive } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    suppliers: Object,
    filters: Object,
});

const showModal = ref(false);
const editingSupplier = ref(null);

const form = useForm({
    name: '',
    contact_name: '',
    contact_email: '',
    contact_phone: '',
    source_platform: 'other',
    lead_time_days: 14,
    notes: '',
    is_active: true,
});

const openCreate = () => {
    editingSupplier.value = null;
    form.reset();
    showModal.value = true;
};

const openEdit = (supplier) => {
    editingSupplier.value = supplier;
    form.name = supplier.name;
    form.contact_name = supplier.contact_name || '';
    form.contact_email = supplier.contact_email || '';
    form.contact_phone = supplier.contact_phone || '';
    form.source_platform = supplier.source_platform;
    form.lead_time_days = supplier.lead_time_days;
    form.notes = supplier.notes || '';
    form.is_active = supplier.is_active;
    showModal.value = true;
};

const submit = () => {
    if (editingSupplier.value) {
        form.put(route('admin.suppliers.update', editingSupplier.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('admin.suppliers.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
};

const deactivate = (supplier) => {
    if (confirm(`Deactivate "${supplier.name}"?`)) {
        router.delete(route('admin.suppliers.destroy', supplier.id));
    }
};

const platformLabel = (p) => ({ alibaba: 'Alibaba', local_factory: 'Local Factory', other: 'Other' }[p] || p);

const filters = reactive({ search: '', platform: '', status: '' });

const applyFilters = () => {
    router.get(route('admin.suppliers.index'), filters, { preserveState: true, replace: true });
};
</script>

<template>
    <AppLayout title="Factories">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Factories</h2>
                <button @click="openCreate"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Factory
                </button>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3">
                    <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search factories…"
                           class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    <select v-model="filters.platform" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Platforms</option>
                        <option value="alibaba">Alibaba</option>
                        <option value="local_factory">Local Factory</option>
                        <option value="other">Other</option>
                    </select>
                    <select v-model="filters.status" @change="applyFilters"
                            class="rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Factory</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Platform</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="suppliers.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No factories found. <button @click="openCreate" class="text-accent underline">Add your first factory.</button>
                                </td>
                            </tr>
                            <tr v-for="supplier in suppliers.data" :key="supplier.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <Link :href="route('admin.suppliers.show', supplier.id)" class="font-medium text-gray-900 hover:text-accent">
                                        {{ supplier.name }}
                                    </Link>
                                    <p v-if="supplier.contact_email" class="text-xs text-gray-400">{{ supplier.contact_email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">
                                        {{ platformLabel(supplier.source_platform) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ supplier.lead_time_days }} days</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ supplier.products_count ?? supplier.products?.length ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full"
                                          :class="supplier.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                        {{ supplier.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('admin.suppliers.show', supplier.id)"
                                              class="text-xs text-gray-500 hover:text-accent px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                                            View
                                        </Link>
                                        <button @click="openEdit(supplier)"
                                                class="text-xs text-gray-500 hover:text-accent px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                                            Edit
                                        </button>
                                        <button v-if="supplier.is_active" @click="deactivate(supplier)"
                                                class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded-lg hover:bg-red-50 transition">
                                            Deactivate
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="suppliers.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing {{ suppliers.from }} – {{ suppliers.to }} of {{ suppliers.total }}</p>
                        <div class="flex gap-2">
                            <Link v-if="suppliers.prev_page_url" :href="suppliers.prev_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</Link>
                            <Link v-if="suppliers.next_page_url" :href="suppliers.next_page_url"
                                  class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-lg text-gray-900">
                        {{ editingSupplier ? 'Edit Factory' : 'Add New Factory' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Factory Name *</label>
                        <input v-model="form.name" type="text" required
                               class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                            <input v-model="form.contact_name" type="text"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input v-model="form.contact_phone" type="text"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                        <input v-model="form.contact_email" type="email"
                               class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source Platform *</label>
                            <select v-model="form.source_platform" required
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent">
                                <option value="alibaba">Alibaba</option>
                                <option value="local_factory">Local Factory</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (days) *</label>
                            <input v-model="form.lead_time_days" type="number" min="1" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes / Terms</label>
                        <textarea v-model="form.notes" rows="3"
                                  placeholder="Private label terms, packaging specs, customization notes…"
                                  class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input v-model="form.is_active" type="checkbox" id="is_active"
                               class="rounded border-gray-300 text-accent focus:ring-accent" />
                        <label for="is_active" class="text-sm text-gray-700">Active factory</label>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="flex-1 py-2.5 bg-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition disabled:opacity-50">
                            {{ form.processing ? 'Saving…' : (editingSupplier ? 'Update Factory' : 'Create Factory') }}
                        </button>
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
