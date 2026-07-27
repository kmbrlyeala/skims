<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    title: {
        type: String,
        default: 'SkimShop'
    }
});
</script>

<template>
    <AppLayout :title="title">
        <slot />
    </AppLayout>
</template>
