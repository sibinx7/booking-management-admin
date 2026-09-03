<script setup lang="ts">
import type { VTextField } from 'vuetify/components';

type VTextFieldProps = InstanceType<typeof VTextField>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: string | number | null;
        label?: string;
        type?: string;
        placeholder?: string;
        variant?: VTextFieldProps['variant'];
        density?: VTextFieldProps['density'];
        prependInnerIcon?: string;
        appendInnerIcon?: string;
        errorMessages?: string | string[];
        required?: boolean;
        disabled?: boolean;
        readonly?: boolean;
    }>(),
    {
        modelValue: '',
        variant: 'outlined',
        density: 'comfortable',
        type: 'text',
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
    <v-text-field
        :model-value="modelValue"
        :label="label"
        :type="type"
        :placeholder="placeholder"
        :variant="variant"
        :density="density"
        :prepend-inner-icon="prependInnerIcon"
        :append-inner-icon="appendInnerIcon"
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
    </v-text-field>
</template>
