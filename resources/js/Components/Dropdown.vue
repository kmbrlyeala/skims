<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: Array,
        default: () => ['py-1', 'bg-white'],
    },
});

let open = ref(false);
const triggerRef = ref(null);
const pos = ref({ top: 0, left: 0, right: 0 });

const updatePosition = () => {
    if (triggerRef.value) {
        const rect = triggerRef.value.getBoundingClientRect();
        pos.value = {
            top: rect.bottom,
            left: rect.left,
            right: window.innerWidth - rect.right,
        };
    }
};

const toggle = () => {
    open.value = !open.value;
    if (open.value) {
        updatePosition();
    }
};

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

const closeOnScroll = (e) => {
    // Only close if we are scrolling something other than the dropdown itself
    if (open.value) {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
    window.addEventListener('scroll', closeOnScroll, true);
    window.addEventListener('resize', closeOnScroll);
});
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    window.removeEventListener('scroll', closeOnScroll, true);
    window.removeEventListener('resize', closeOnScroll);
});

const widthClass = computed(() => {
    return {
        '48': 'w-48',
    }[props.width.toString()];
});
</script>

<template>
    <div ref="triggerRef" class="relative inline-block text-left">
        <div @click.stop="toggle" class="cursor-pointer inline-flex items-center justify-center">
            <slot name="trigger" />
        </div>

        <Teleport to="body">
            <!-- Full Screen Dropdown Overlay -->
            <div v-show="open" class="fixed inset-0 z-[99]" @click.stop="open = false" />

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div
                    v-show="open"
                    class="fixed z-[100] mt-1 rounded-md shadow-lg"
                    :class="[widthClass]"
                    :style="[
                        { top: pos.top + 'px' },
                        align === 'right' ? { right: pos.right + 'px' } : { left: pos.left + 'px' }
                    ]"
                    style="display: none;"
                    @click="open = false"
                >
                    <div class="rounded-md ring-1 ring-black ring-opacity-5 overflow-hidden" :class="contentClasses">
                        <slot name="content" />
                    </div>
                </div>
            </transition>
        </Teleport>
    </div>
</template>
