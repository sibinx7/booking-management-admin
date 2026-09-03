<script setup lang="ts">
import type { VCard } from 'vuetify/components';

type VCardProps = InstanceType<typeof VCard>['$props'];

defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        elevation?: number | string;
        variant?: VCardProps['variant'];
        color?: string;
        title?: string;
        subtitle?: string;
    }>(),
    {
        elevation: 1,
        variant: 'elevated',
    },
);
</script>

<template>
    <v-card
        :elevation="elevation"
        :variant="variant"
        :color="color"
        :title="title"
        :subtitle="subtitle"
        v-bind="$attrs"
        rounded="lg"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </v-card>
</template>
