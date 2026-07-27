<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <CustomerLayout title="Account Settings">
        <Head title="Account Settings" />

        <div class="max-w-6xl mx-auto space-y-8 pb-12">
            
            <!-- Header -->
            <div class="flex items-end justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Account Settings</h1>
                    <p class="mt-2 text-sm text-gray-500">Manage your preferences, notifications, and localization.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Sidebar Navigation for Settings -->
                <div class="space-y-2">
                    <div class="bg-brand-pink/10 text-brand-pink font-bold rounded-xl px-4 py-3 cursor-pointer">General Preferences</div>
                    <Link :href="route('profile.show')" class="block text-gray-600 font-medium rounded-xl px-4 py-3 hover:bg-gray-50 transition">Security & Profile Info</Link>
                    <div class="block text-gray-600 font-medium rounded-xl px-4 py-3 hover:bg-gray-50 transition cursor-pointer">Notification Settings</div>
                </div>

                <!-- Main Settings Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Localization -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Localization</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Language</label>
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                                    <option>English (US)</option>
                                    <option>Tagalog</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Currency</label>
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-brand-pink focus:border-brand-pink transition text-sm">
                                    <option>PHP (₱)</option>
                                    <option>USD ($)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Email Notifications -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Email Notifications</h3>
                        <p class="text-sm text-gray-500 mb-6">Choose what we can send to {{ user?.email || 'your email' }}.</p>
                        
                        <!-- Toggle Items -->
                        <div class="flex items-center justify-between py-4 border-b border-gray-50">
                            <div>
                                <h4 class="font-bold text-gray-800">Order Updates</h4>
                                <p class="text-xs text-gray-500 mt-1">Receive notifications about your order status and shipping updates.</p>
                            </div>
                            <button class="w-12 h-6 rounded-full bg-brand-pink relative transition-colors duration-300 focus:outline-none">
                                <span class="absolute right-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-300"></span>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between py-4 border-b border-gray-50">
                            <div>
                                <h4 class="font-bold text-gray-800">Exclusive Offers & Promotions</h4>
                                <p class="text-xs text-gray-500 mt-1">Get early access to sales, new arrivals, and special discounts.</p>
                            </div>
                            <button class="w-12 h-6 rounded-full bg-brand-pink relative transition-colors duration-300 focus:outline-none">
                                <span class="absolute right-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-300"></span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between py-4">
                            <div>
                                <h4 class="font-bold text-gray-800">Product Review Reminders</h4>
                                <p class="text-xs text-gray-500 mt-1">Help others by reviewing products you've purchased.</p>
                            </div>
                            <button class="w-12 h-6 rounded-full bg-gray-200 relative transition-colors duration-300 focus:outline-none">
                                <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-300"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button class="px-8 py-3 bg-brand-pink text-white rounded-xl font-bold hover:bg-brand-pink-hover transition shadow-lg shadow-brand-pink/30 hover:-translate-y-0.5">
                            Save Preferences
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </CustomerLayout>
</template>
