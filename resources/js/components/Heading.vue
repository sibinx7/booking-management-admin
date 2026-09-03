<script setup lang="ts">
withDefaults(
    defineProps<{
        title: string;
        description?: string;
        variant?: 'large' | 'small';
    }>(),
    {
        variant: 'large',
    },
);
</script>

<template>
    <div class="mb-6">
        <h1
            :class="[
                variant === 'large' ? 'text-h5' : 'text-h6',
                'font-weight-bold text-grey-darken-3 mb-1',
            ]"
        >
            {{ title }}
        </h1>
        <p v-if="description" class="text-body-2 text-grey-darken-1 mb-0">
            {{ description }}
        </p>
    </div>
</template>
