<script setup lang="ts">
import type { VAlert } from 'vuetify/components';

type VAlertProps = InstanceType<typeof VAlert>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        type?: VAlertProps['type'];
        variant?: VAlertProps['variant'];
        density?: VAlertProps['density'];
        title?: string;
        text?: string;
        closable?: boolean;
    }>(),
    {
        type: 'info',
        variant: 'tonal',
        density: 'comfortable',
        closable: false,
    },
);
</script>

<template>
    <v-alert
        :type="type"
        :variant="variant"
        :density="density"
        :title="title"
        :text="text"
        :closable="closable"
        v-bind="$attrs"
        rounded="lg"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-alert>
</template>
