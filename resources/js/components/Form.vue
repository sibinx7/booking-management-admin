<script setup lang="ts">
import type { VForm } from 'vuetify/components';

type VFormProps = InstanceType<typeof VForm>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        modelValue?: boolean | null;
        disabled?: boolean;
        readonly?: boolean;
        validateOn?: VFormProps['validateOn'];
    }>(),
    {
        modelValue: null,
        disabled: false,
        readonly: false,
        validateOn: 'input',
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean | null): void;
    (e: 'submit', event: SubmitEvent): void;
}>();
</script>

<template>
    <v-form
        :model-value="modelValue"
        :disabled="disabled"
        :readonly="readonly"
        :validate-on="validateOn"
        v-bind="$attrs"
        @update:model-value="emit('update:modelValue', $event)"
        @submit.prevent="emit('submit', $event)"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-form>
</template>
