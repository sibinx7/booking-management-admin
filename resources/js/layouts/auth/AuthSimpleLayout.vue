<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <v-app class="bg-grey-lighten-4">
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
                            <h2 v-if="title" class="text-h5 font-weight-bold text-grey-darken-3 mb-1">
                                {{ title }}
                            </h2>
                            <p v-if="description" class="text-body-2 text-grey-darken-1 mb-0">
                                {{ description }}
                            </p>
                        </div>

                        <v-card elevation="2" rounded="lg" class="pa-6 pa-sm-8 bg-white">
                            <slot />
                        </v-card>
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
