<script setup lang="ts">
import type { VBtn } from 'vuetify/components';

type VBtnProps = InstanceType<typeof VBtn>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        color?: string;
        variant?: VBtnProps['variant'];
        size?: VBtnProps['size'];
        block?: boolean;
        loading?: boolean;
        disabled?: boolean;
    }>(),
    {
        color: 'primary',
        variant: 'elevated',
        size: 'default',
        block: false,
        loading: false,
        disabled: false,
    },
);
</script>

<template>
    <v-btn
        :color="color"
        :variant="variant"
        :size="size"
        :block="block"
        :loading="loading"
        :disabled="disabled"
        v-bind="$attrs"
        rounded="lg"
        class="text-none font-weight-medium"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-btn>
</template>
