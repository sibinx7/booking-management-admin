<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: boolean | any[] | null;
        label?: string;
        color?: string;
        density?: 'default' | 'comfortable' | 'compact';
        hideDetails?: boolean | 'auto';
        disabled?: boolean;
        inset?: boolean;
    }>(),
    {
        modelValue: false,
        color: 'primary',
        density: 'compact',
        hideDetails: true,
        disabled: false,
        inset: true,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean | any[] | null): void;
}>();
</script>

<template>
    <v-switch
        :model-value="modelValue"
        :label="label"
        :color="color"
        :density="density"
        :hide-details="hideDetails"
        :disabled="disabled"
        :inset="inset"
        v-bind="$attrs"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-switch>
</template>
