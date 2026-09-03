<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { updateTheme, useAppearance } from '@/composables/useAppearance';
import { edit as editAppearance } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Appearance settings',
                href: editAppearance(),
            },
        ],
    },
});

const { appearance } = useAppearance();

const setMode = (mode: 'light' | 'dark' | 'system') => {
    updateTheme(mode);
};
</script>

<template>
    <Head title="Appearance settings" />

    <div class="mb-6">
        <h2 class="text-h6 font-weight-bold text-grey-darken-3 mb-1">Appearance Settings</h2>
        <p class="text-body-2 text-grey-darken-1 mb-0">Update your dashboard display and theme preferences</p>
    </div>

    <v-row class="max-w-md">
        <v-col cols="12" sm="4">
            <v-card
                :variant="appearance === 'light' ? 'tonal' : 'outlined'"
                :color="appearance === 'light' ? 'primary' : undefined"
                class="pa-4 text-center cursor-pointer"
                rounded="lg"
                @click="setMode('light')"
            >
                <v-icon icon="mdi-white-balance-sunny" size="32" class="mb-2" />
                <div class="font-weight-medium text-body-2">Light</div>
            </v-card>
        </v-col>

        <v-col cols="12" sm="4">
            <v-card
                :variant="appearance === 'dark' ? 'tonal' : 'outlined'"
                :color="appearance === 'dark' ? 'primary' : undefined"
                class="pa-4 text-center cursor-pointer"
                rounded="lg"
                @click="setMode('dark')"
            >
                <v-icon icon="mdi-weather-night" size="32" class="mb-2" />
                <div class="font-weight-medium text-body-2">Dark</div>
            </v-card>
        </v-col>

        <v-col cols="12" sm="4">
            <v-card
                :variant="appearance === 'system' ? 'tonal' : 'outlined'"
                :color="appearance === 'system' ? 'primary' : undefined"
                class="pa-4 text-center cursor-pointer"
                rounded="lg"
                @click="setMode('system')"
            >
                <v-icon icon="mdi-laptop" size="32" class="mb-2" />
                <div class="font-weight-medium text-body-2">System</div>
            </v-card>
        </v-col>
    </v-row>
</template>
