<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    sku: '',
    name: '',
    description: '',
    price: '',
    reorder_point: 0,
    is_active: true,
    photos: [],
});

const photoPreviews = ref([]);

const handlePhotoChange = (e) => {
    const files = Array.from(e.target.files);
    form.photos = files;
    photoPreviews.value = files.map(f => URL.createObjectURL(f));
};

const submit = () => {
    form.post(route('admin.products.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <AppLayout title="Create Product">
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.products.index')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Basic Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Product Details</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                                <input v-model="form.sku" type="text" required placeholder="e.g. SKM-BRA-BLK-M"
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent font-mono" />
                                <p v-if="form.errors.sku" class="text-xs text-red-600 mt-1">{{ form.errors.sku }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) *</label>
                                <input v-model="form.price" type="number" step="0.01" min="0" required
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                                <p v-if="form.errors.price" class="text-xs text-red-600 mt-1">{{ form.errors.price }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                            <input v-model="form.name" type="text" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea v-model="form.description" rows="4"
                                      class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"></textarea>
                        </div>
                    </div>

                    <!-- Inventory Settings -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Inventory Settings</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Point *</label>
                            <p class="text-xs text-gray-400 mb-2">
                                System alerts when on-hand stock drops to or below this number. Set based on sales velocity × lead time + safety buffer.
                            </p>
                            <input v-model="form.reorder_point" type="number" min="0" required
                                   class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                            <p v-if="form.errors.reorder_point" class="text-xs text-red-600 mt-1">{{ form.errors.reorder_point }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input v-model="form.is_active" type="checkbox" id="is_active_create"
                                   class="rounded border-gray-300 text-accent focus:ring-accent" />
                            <label for="is_active_create" class="text-sm text-gray-700">List as active product</label>
                        </div>
                        <p class="text-xs text-gray-400">
                            Stock quantity starts at 0 and will update automatically when you receive goods via a Purchase Order.
                        </p>
                    </div>

                    <!-- Photos -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Photos</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Photos</label>
                            <input type="file" multiple accept="image/*" @change="handlePhotoChange"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent file:text-white hover:file:bg-opacity-90" />
                            <p v-if="form.errors['photos.0']" class="text-xs text-red-600 mt-1">{{ form.errors['photos.0'] }}</p>
                        </div>
                        <div v-if="photoPreviews.length > 0" class="flex flex-wrap gap-3">
                            <div v-for="(url, i) in photoPreviews" :key="i"
                                 class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100">
                                <img :src="url" class="w-full h-full object-cover" />
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" :disabled="form.processing"
                                class="flex-1 py-3 bg-accent text-white text-sm font-medium rounded-xl hover:bg-opacity-90 transition disabled:opacity-50">
                            {{ form.processing ? 'Creating…' : 'Create Product' }}
                        </button>
                        <Link :href="route('admin.products.index')"
                              class="px-6 py-3 border border-gray-200 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
