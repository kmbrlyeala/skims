<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const paymentMethods = ref([
    {
        id: 1,
        type: 'Visa',
        last4: '4242',
        expiry: '12/28',
        cardholder: 'Jane Doe',
        isDefault: true,
        style: 'bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-800'
    },
    {
        id: 2,
        type: 'Mastercard',
        last4: '5555',
        expiry: '08/26',
        cardholder: 'Jane Doe',
        isDefault: false,
        style: 'bg-gradient-to-br from-gray-900 via-gray-800 to-black'
    }
]);

const showAddModal = ref(false);

const deleteMethod = (id) => {
    paymentMethods.value = paymentMethods.value.filter(p => p.id !== id);
};
</script>

<template>
    <CustomerLayout title="Payment Methods">
        <Head title="Payment Methods" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Payment Methods</h1>
                    <p class="mt-2 text-sm text-gray-500">Securely manage your saved payment options.</p>
                </div>
                <button @click="showAddModal = true" class="px-6 py-2.5 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-lg hover:shadow-brand-pink/30 flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Payment Method
                </button>
            </div>

            <!-- Payment Methods Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card Item -->
                <div v-for="card in paymentMethods" :key="card.id" class="group relative">
                    <!-- Default Badge -->
                    <div v-if="card.isDefault" class="absolute -top-3 -right-3 z-10 px-3 py-1 bg-brand-pink text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">
                        Default
                    </div>
                    
                    <!-- Credit Card Visual -->
                    <div :class="['relative h-48 rounded-2xl p-6 text-white overflow-hidden shadow-xl transition-transform duration-300 group-hover:-translate-y-2', card.style]">
                        <!-- Decorative overlay -->
                        <div class="absolute inset-0 bg-white/5 opacity-50 backdrop-blur-3xl"></div>
                        <div class="absolute -right-10 -bottom-10 h-32 w-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -top-10 h-32 w-32 bg-white/10 rounded-full blur-2xl"></div>
                        
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <!-- Chip Icon -->
                                <svg class="h-8 w-10 text-yellow-400 opacity-80" viewBox="0 0 40 30" fill="currentColor">
                                    <path d="M5 0h30a5 5 0 015 5v20a5 5 0 01-5 5H5a5 5 0 01-5-5V5a5 5 0 015-5zm5 5h20v5H10V5zm0 10h20v5H10v-5zm-5-5h5v5H5v-5zm25 0h5v5h-5v-5z" />
                                </svg>
                                <!-- Card Brand Text -->
                                <span class="font-bold text-lg tracking-wider opacity-90 italic">{{ card.type }}</span>
                            </div>
                            
                            <div>
                                <div class="text-xl font-mono tracking-[0.2em] mb-2 flex gap-4">
                                    <span>••••</span>
                                    <span>••••</span>
                                    <span>••••</span>
                                    <span>{{ card.last4 }}</span>
                                </div>
                                <div class="flex justify-between text-xs font-medium uppercase tracking-widest opacity-80">
                                    <span>{{ card.cardholder }}</span>
                                    <span>Exp {{ card.expiry }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 mt-4 px-2">
                        <button class="text-sm font-bold text-gray-500 hover:text-brand-pink transition">Edit</button>
                        <span class="text-gray-300">|</span>
                        <button @click="deleteMethod(card.id)" class="text-sm font-bold text-gray-400 hover:text-red-500 transition">Remove</button>
                    </div>
                </div>

                <!-- PayPal Item -->
                <div class="group relative">
                    <div class="relative h-48 rounded-2xl bg-white border border-gray-200 p-6 flex flex-col justify-center items-center text-center shadow-sm hover:shadow-lg transition-transform duration-300 group-hover:-translate-y-2">
                        <svg class="h-10 w-10 text-blue-600 mb-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/>
                        </svg>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">jane.doe@example.com</h3>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">PayPal Connected</p>
                    </div>
                    <!-- Actions -->
                    <div class="flex items-center gap-4 mt-4 px-2">
                        <button class="text-sm font-bold text-gray-400 hover:text-red-500 transition">Disconnect</button>
                    </div>
                </div>

            </div>

            <!-- Modal Mockup (Visually displayed when true) -->
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Add Payment Method</h3>
                    <form class="space-y-4" @submit.prevent="showAddModal = false">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Card Number</label>
                            <input type="text" placeholder="0000 0000 0000 0000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm font-mono">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Expiry Date</label>
                                <input type="text" placeholder="MM/YY" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">CVC</label>
                                <input type="text" placeholder="123" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm text-center">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Cardholder Name</label>
                            <input type="text" placeholder="Name on card" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" id="defaultPay" class="rounded text-brand-pink focus:ring-brand-pink h-4 w-4">
                            <label for="defaultPay" class="text-sm text-gray-700">Set as default payment method</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                            <button type="button" @click="showAddModal = false" class="px-6 py-2.5 text-gray-600 font-bold text-sm hover:text-gray-900 transition">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-md">Add Card</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
