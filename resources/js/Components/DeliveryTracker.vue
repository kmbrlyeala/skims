<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
    interactive: {
        type: Boolean,
        default: false,
    }
});

// Database statuses mapped to e-commerce UI labels
const stepsConfig = [
    { id: 'pending', label: 'ORDER PLACED', icon: 'clipboard' },
    { id: 'processing', label: 'PACKING', icon: 'wallet' },
    { id: 'shipped', label: 'IN TRANSIT', icon: 'truck' },
    { id: 'delivered', label: 'ORDER DELIVERED', icon: 'box' },
];

const statusSteps = stepsConfig.map(s => s.id);
const currentStepIndex = computed(() => Math.max(0, statusSteps.indexOf(props.status)));

const emit = defineEmits(['update:status']);

const setStatus = (step) => {
    if (props.interactive) {
        emit('update:status', step);
    }
};
</script>

<template>
    <div class="w-full px-4 py-8 max-w-3xl mx-auto">
        <div class="flex items-start justify-between w-full">
            <template v-for="(step, i) in stepsConfig" :key="step.id">
                
                <!-- The Step Node -->
                <div class="flex flex-col items-center relative z-10 w-28 group" 
                     :class="interactive ? 'cursor-pointer' : 'cursor-default'"
                     @click="setStatus(step.id)">
                    
                    <!-- Icon Circle -->
                    <div 
                        class="flex h-14 w-14 items-center justify-center rounded-full border-[3px] bg-white transition-all duration-300 group-hover:scale-105"
                        :class="i <= currentStepIndex 
                            ? 'border-emerald-500 text-emerald-500 shadow-[0_4px_12px_rgba(16,185,129,0.25)]' 
                            : 'border-slate-200 text-slate-300'"
                    >
                        <!-- Clipboard (Order Placed) -->
                        <svg v-if="step.icon === 'clipboard'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <!-- Wallet (Order Paid) -->
                        <svg v-else-if="step.icon === 'wallet'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        <!-- Truck (Order Shipped) -->
                        <svg v-else-if="step.icon === 'truck'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        <!-- Box (Order Received) -->
                        <svg v-else-if="step.icon === 'box'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>

                    <!-- Label -->
                    <p class="mt-3 text-[13px] tracking-wide text-center leading-snug" 
                       :class="i <= currentStepIndex ? 'font-bold text-slate-800' : 'font-medium text-slate-400'">
                        {{ step.label }}
                    </p>
                    <!-- Sub-label -->
                    <p v-if="i <= currentStepIndex" class="mt-1 text-[11px] font-bold tracking-wider uppercase"
                       :class="i === currentStepIndex ? 'text-emerald-500' : 'text-slate-400'">
                        {{ i === currentStepIndex ? 'In Progress' : 'Completed' }}
                    </p>
                </div>

                <!-- The Connecting Line -->
                <div v-if="i < stepsConfig.length - 1" class="flex-1 mt-7 h-[3px] mx-1 rounded-full transition-colors duration-500"
                     :class="i < currentStepIndex ? 'bg-emerald-500' : 'bg-slate-200'">
                </div>

            </template>
        </div>
    </div>
</template>
