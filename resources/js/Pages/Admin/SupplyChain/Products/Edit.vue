<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ product: Object });

const form = useForm({
    name: props.product.name,
    description: props.product.description ?? '',
    price: props.product.price,
    reorder_point: props.product.reorder_point,
    is_active: props.product.is_active,
    photos: [],
    remove_photos: [],
});

const newPhotoPreviews = ref([]);
const existingPhotos = ref([...props.product.photo_urls].map((url, i) => ({
    url,
    path: props.product.photos[i],
    remove: false,
})));

const handlePhotoChange = (e) => {
    const files = Array.from(e.target.files);
    form.photos = files;
    newPhotoPreviews.value = files.map(f => URL.createObjectURL(f));
};

const toggleRemovePhoto = (photo) => {
    photo.remove = !photo.remove;
    form.remove_photos = existingPhotos.value.filter(p => p.remove).map(p => p.path);
};

const submit = () => {
    form.post(route('admin.products.update', props.product.id), {
        forceFormData: true,
        _method: 'put',
    });
};
</script>

<template>
    <AppLayout :title="`Edit: ${product.name}`">
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.products.index')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Read-only SKU + Stock Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-blue-500 uppercase tracking-wide font-semibold">SKU</p>
                        <p class="text-sm font-mono font-semibold text-blue-900 mt-1">{{ product.sku }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 uppercase tracking-wide font-semibold">On Hand</p>
                        <p class="text-sm font-semibold text-blue-900 mt-1">{{ product.on_hand_qty }} units</p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 uppercase tracking-wide font-semibold">Incoming</p>
                        <p class="text-sm font-semibold text-blue-900 mt-1">{{ product.incoming_qty }} units</p>
                    </div>
                    <p class="col-span-3 text-xs text-blue-400">Stock counts are read-only here — they update automatically when goods are received.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Product Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                                <input v-model="form.name" type="text" required
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                                <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) *</label>
                                <input v-model="form.price" type="number" step="0.01" min="0" required
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Point *</label>
                                <input v-model="form.reorder_point" type="number" min="0" required
                                       class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent" />
                                <p class="text-xs text-gray-400 mt-1">Alert when stock ≤ this value</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea v-model="form.description" rows="4"
                                      class="w-full rounded-lg border-gray-200 text-sm focus:ring-accent focus:border-accent"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input v-model="form.is_active" type="checkbox" id="edit_is_active"
                                   class="rounded border-gray-300 text-accent focus:ring-accent" />
                            <label for="edit_is_active" class="text-sm text-gray-700">Active listing</label>
                        </div>
                    </div>

                    <!-- Photos -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Photos</h3>

                        <!-- Existing -->
                        <div v-if="existingPhotos.length > 0">
                            <p class="text-xs text-gray-400 mb-2">Click to remove existing photos</p>
                            <div class="flex flex-wrap gap-3">
                                <div v-for="photo in existingPhotos" :key="photo.path"
                                     @click="toggleRemovePhoto(photo)"
                                     class="relative w-20 h-20 rounded-xl overflow-hidden cursor-pointer border-2 transition"
                                     :class="photo.remove ? 'border-red-400 opacity-50' : 'border-transparent'">
                                    <img :src="photo.url" class="w-full h-full object-cover" />
                                    <div v-if="photo.remove"
                                         class="absolute inset-0 bg-red-500/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New uploads -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Add New Photos</label>
                            <input type="file" multiple accept="image/*" @change="handlePhotoChange"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent file:text-white hover:file:bg-opacity-90" />
                        </div>
                        <div v-if="newPhotoPreviews.length > 0" class="flex flex-wrap gap-3">
                            <div v-for="(url, i) in newPhotoPreviews" :key="i"
                                 class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100">
                                <img :src="url" class="w-full h-full object-cover" />
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" :disabled="form.processing"
                                class="flex-1 py-3 bg-accent text-white text-sm font-medium rounded-xl hover:bg-opacity-90 transition disabled:opacity-50">
                            {{ form.processing ? 'Saving…' : 'Save Changes' }}
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
