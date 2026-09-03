<script setup lang="ts">
import type { VFileInput } from 'vuetify/components';

type VFileInputProps = InstanceType<typeof VFileInput>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: File | File[] | null;
        label?: string;
        placeholder?: string;
        accept?: string;
        multiple?: boolean;
        chips?: boolean;
        showSize?: boolean;
        prependIcon?: string;
        variant?: VFileInputProps['variant'];
        density?: VFileInputProps['density'];
        errorMessages?: string | string[];
        disabled?: boolean;
    }>(),
    {
        variant: 'outlined',
        density: 'comfortable',
        prependIcon: 'mdi-paperclip',
        multiple: false,
        chips: true,
        showSize: true,
        disabled: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: File | File[] | null): void;
}>();
</script>

<template>
    <v-file-input
        :model-value="modelValue"
        :label="label"
        :placeholder="placeholder"
        :accept="accept"
        :multiple="multiple"
        :chips="chips"
        :show-size="showSize"
        :prepend-icon="prependIcon"
        :variant="variant"
        :density="density"
        :error-messages="errorMessages"
        :disabled="disabled"
        v-bind="$attrs"
        rounded="lg"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-file-input>
</template>
