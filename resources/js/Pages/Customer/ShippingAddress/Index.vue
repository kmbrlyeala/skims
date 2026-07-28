<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showEditModal = ref(false);
const showDeleteModal = ref(false);
const activeItem = ref(null);

const openEditModal = (item) => {
    activeItem.value = item;
    showEditModal.value = true;
};

const openDeleteModal = (item) => {
    activeItem.value = item;
    showDeleteModal.value = true;
};

const addresses = ref([
    {
        id: 1,
        name: 'Home',
        recipient: 'Jane Doe',
        street: '123 Magnolia Lane',
        city: 'Quezon City',
        province: 'Metro Manila',
        zip: '1100',
        phone: '+63 917 123 4567',
        isDefaultShipping: true,
        isDefaultBilling: true
    },
    {
        id: 2,
        name: 'Office',
        recipient: 'Jane Doe (SkimShop)',
        street: '45B Ayala Avenue, Corporate Center',
        city: 'Makati City',
        province: 'Metro Manila',
        zip: '1226',
        phone: '+63 917 123 4567',
        isDefaultShipping: false,
        isDefaultBilling: false
    }
]);

const showAddModal = ref(false);

const deleteAddress = (id) => {
    addresses.value = addresses.value.filter(a => a.id !== id);
};
</script>

<template>
    <CustomerLayout title="Shipping Addresses">
        <Head title="My Addresses" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Saved Addresses</h1>
                    <p class="mt-2 text-sm text-gray-500">Manage your shipping and billing addresses.</p>
                </div>
                <button @click="showAddModal = true" class="px-6 py-2.5 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-lg hover:shadow-brand-pink/30 flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Address
                </button>
            </div>

            <!-- Addresses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="address in addresses" :key="address.id" class="bg-white/60 backdrop-blur-xl border border-white/40 rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 relative group">
                    
                    <!-- Badges -->
                    <div class="absolute top-6 right-6 flex flex-col items-end gap-1">
                        <span v-if="address.isDefaultShipping" class="px-2.5 py-1 bg-brand-pink-light/50 text-brand-pink text-[9px] font-bold uppercase tracking-widest rounded-full">Default Shipping</span>
                        <span v-if="address.isDefaultBilling" class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-widest rounded-full">Default Billing</span>
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                            <svg v-if="address.name.toLowerCase() === 'home'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ address.name }}</h3>
                    </div>

                    <div class="space-y-1 text-sm text-gray-600 mb-6">
                        <p class="font-semibold text-gray-800">{{ address.recipient }}</p>
                        <p>{{ address.street }}</p>
                        <p>{{ address.city }}, {{ address.province }} {{ address.zip }}</p>
                        <p class="pt-2 text-gray-500">{{ address.phone }}</p>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Actions">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink as="button" @click="openEditModal(address.id)">
                                    Edit Address
                                </DropdownLink>
                                <div class="border-t border-slate-100"></div>
                                <DropdownLink as="button" @click="openDeleteModal(address.id)" class="!text-red-600 hover:!bg-red-50">
                                    Delete
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>

            <!-- Modal Mockup (Visually displayed when true) -->
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Add New Address</h3>
                    <form class="space-y-4" @submit.prevent="showAddModal = false">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Address Label</label>
                                <input type="text" placeholder="e.g. Home, Office" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Recipient Name</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Street Address</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">City</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Postal Code</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" id="defaultShip" class="rounded text-brand-pink focus:ring-brand-pink h-4 w-4">
                            <label for="defaultShip" class="text-sm text-gray-700">Set as default shipping address</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                            <button type="button" @click="showAddModal = false" class="px-6 py-2.5 text-gray-600 font-bold text-sm hover:text-gray-900 transition">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-md">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Modal Placeholder -->
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Edit Address</h3>
                    <p class="text-sm text-gray-600 mb-6">Editing address ID: {{ activeItem }}</p>
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2.5 text-gray-600 font-bold text-sm hover:text-gray-900 transition">Cancel</button>
                        <button type="button" @click="showEditModal = false" class="px-6 py-2.5 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-md">Save Changes</button>
                    </div>
                </div>
            </div>

            <!-- Delete Modal Placeholder -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showDeleteModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Confirm Deletion</h3>
                    <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this address? This action cannot be undone.</p>
                    <div class="flex justify-center gap-3">
                        <button type="button" @click="showDeleteModal = false" class="px-6 py-2.5 text-gray-600 font-bold text-sm hover:text-gray-900 transition">Cancel</button>
                        <button type="button" @click="deleteAddress(activeItem); showDeleteModal = false" class="px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-md">Delete</button>
                    </div>
                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
