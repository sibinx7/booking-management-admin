<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: any;
        label?: string;
        items?: Array<{ label: string; value: any; disabled?: boolean }>;
        inline?: boolean;
        color?: string;
        density?: 'default' | 'comfortable' | 'compact';
        errorMessages?: string | string[];
        disabled?: boolean;
    }>(),
    {
        inline: true,
        color: 'primary',
        density: 'compact',
        disabled: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();
</script>

<template>
    <v-radio-group
        :model-value="modelValue"
        :label="label"
        :inline="inline"
        :color="color"
        :density="density"
        :error-messages="errorMessages"
        :disabled="disabled"
        v-bind="$attrs"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <template v-if="items && items.length">
            <v-radio
                v-for="item in items"
                :key="String(item.value)"
                :label="item.label"
                :value="item.value"
                :disabled="item.disabled"
            />
        </template>
        <template v-else>
            <slot />
        </template>
    </v-radio-group>
</template>
