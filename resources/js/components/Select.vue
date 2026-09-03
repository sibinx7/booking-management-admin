<script setup lang="ts">
import type { VSelect } from 'vuetify/components';

type VSelectProps = InstanceType<typeof VSelect>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: any;
        items?: any[];
        label?: string;
        itemTitle?: string;
        itemValue?: string;
        placeholder?: string;
        variant?: VSelectProps['variant'];
        density?: VSelectProps['density'];
        prependInnerIcon?: string;
        errorMessages?: string | string[];
        multiple?: boolean;
        chips?: boolean;
        clearable?: boolean;
        disabled?: boolean;
        readonly?: boolean;
    }>(),
    {
        variant: 'outlined',
        density: 'comfortable',
        itemTitle: 'title',
        itemValue: 'value',
        clearable: false,
        disabled: false,
        readonly: false,
        multiple: false,
        chips: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();
</script>

<template>
    <v-select
        :model-value="modelValue"
        :items="items"
        :label="label"
        :item-title="itemTitle"
        :item-value="itemValue"
        :placeholder="placeholder"
        :variant="variant"
        :density="density"
        :prepend-inner-icon="prependInnerIcon"
        :error-messages="errorMessages"
        :multiple="multiple"
        :chips="chips"
        :clearable="clearable"
        :disabled="disabled"
        :readonly="readonly"
        v-bind="$attrs"
        rounded="lg"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-select>
</template>
