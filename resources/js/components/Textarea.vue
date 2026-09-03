<script setup lang="ts">
import type { VTextarea } from 'vuetify/components';

type VTextareaProps = InstanceType<typeof VTextarea>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: string | number | null;
        label?: string;
        placeholder?: string;
        rows?: number | string;
        autoGrow?: boolean;
        variant?: VTextareaProps['variant'];
        density?: VTextareaProps['density'];
        prependInnerIcon?: string;
        errorMessages?: string | string[];
        required?: boolean;
        disabled?: boolean;
        readonly?: boolean;
    }>(),
    {
        modelValue: '',
        rows: 3,
        autoGrow: true,
        variant: 'outlined',
        density: 'comfortable',
        required: false,
        disabled: false,
        readonly: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number | null): void;
}>();
</script>

<template>
    <v-textarea
        :model-value="modelValue"
        :label="label"
        :placeholder="placeholder"
        :rows="rows"
        :auto-grow="autoGrow"
        :variant="variant"
        :density="density"
        :prepend-inner-icon="prependInnerIcon"
        :error-messages="errorMessages"
        :required="required"
        :disabled="disabled"
        :readonly="readonly"
        v-bind="$attrs"
        rounded="lg"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-textarea>
</template>
