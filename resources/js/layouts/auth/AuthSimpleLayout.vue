<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Card } from '@/components';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <v-app>
        <v-main class="d-flex align-center justify-center min-vh-100 py-10">
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" sm="8" md="6" lg="4">
                        <div class="text-center mb-6">
                            <Link :href="home()" class="text-decoration-none">
                                <v-avatar color="primary" size="56" class="mb-3 elevation-2">
                                    <v-icon icon="mdi-spa" size="32" color="white" />
                                </v-avatar>
                            </Link>
                            <h2 v-if="title" class="text-h5 font-weight-bold mb-1">
                                {{ title }}
                            </h2>
                            <p v-if="description" class="text-body-2 text-medium-emphasis mb-0">
                                {{ description }}
                            </p>
                        </div>

                        <Card elevation="3" class="pa-6 pa-sm-8">
                            <slot />
                        </Card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.min-vh-100 {
    min-height: 100vh;
}
</style>
