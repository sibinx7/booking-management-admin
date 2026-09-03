<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';
</script>

<template>
    <Head title="Welcome" />

    <v-app class="bg-grey-lighten-4">
        <v-app-bar elevation="1" color="surface">
            <div class="d-flex align-center ml-4">
                <v-avatar color="primary" size="36" class="mr-2">
                    <v-icon icon="mdi-spa" color="white" size="22" />
                </v-avatar>
                <span class="text-h6 font-weight-bold text-grey-darken-3">Spa Management</span>
            </div>

            <v-spacer />

            <div class="mr-4">
                <v-btn
                    v-if="$page.props.auth?.user"
                    :to="dashboard.url()"
                    color="primary"
                    variant="flat"
                    class="text-capitalize"
                >
                    Dashboard
                </v-btn>
                <v-btn
                    v-else
                    :to="login.url()"
                    color="primary"
                    variant="flat"
                    prepend-icon="mdi-login"
                    class="text-capitalize"
                >
                    Log in
                </v-btn>
            </div>
        </v-app-bar>

        <v-main class="d-flex align-center justify-center min-vh-100 py-12">
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" md="8" lg="6" class="text-center">
                        <v-avatar color="primary" size="80" class="mb-4 elevation-3">
                            <v-icon icon="mdi-spa" color="white" size="48" />
                        </v-avatar>

                        <h1 class="text-h4 font-weight-bold text-grey-darken-3 mb-3">
                            Spa & Booking Management Admin
                        </h1>

                        <p class="text-body-1 text-grey-darken-1 mb-6">
                            Manage spa services, therapists, employees, availability schedules, and monthly salary payslips in one unified portal.
                        </p>

                        <div class="d-flex justify-center ga-4">
                            <v-btn
                                v-if="$page.props.auth?.user"
                                :to="dashboard.url()"
                                color="primary"
                                size="large"
                                class="text-capitalize font-weight-bold"
                                elevation="2"
                            >
                                Open Dashboard
                            </v-btn>
                            <v-btn
                                v-else
                                :to="login.url()"
                                color="primary"
                                size="large"
                                class="text-capitalize font-weight-bold"
                                elevation="2"
                            >
                                Access Admin Portal
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.min-vh-100 {
    min-height: calc(100vh - 64px);
}
</style>
