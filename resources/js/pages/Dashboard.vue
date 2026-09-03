<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard.url(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div class="mb-6">
            <h1 class="text-h5 font-weight-bold text-grey-darken-3 mb-1">Spa Overview</h1>
            <p class="text-body-2 text-grey-darken-1 mb-0">Welcome to your spa management control center</p>
        </div>

        <v-row>
            <v-col cols="12" sm="6" md="3">
                <v-card elevation="1" rounded="lg" class="pa-4 bg-white">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-grey text-uppercase">Employees</span>
                        <v-avatar color="primary" variant="tonal" size="36">
                            <v-icon icon="mdi-account-group-outline" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold text-grey-darken-3">12</div>
                    <div class="text-caption text-success font-weight-medium mt-1">
                        Active staff members
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card elevation="1" rounded="lg" class="pa-4 bg-white">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-grey text-uppercase">Therapists</span>
                        <v-avatar color="secondary" variant="tonal" size="36">
                            <v-icon icon="mdi-account-heart-outline" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold text-grey-darken-3">8</div>
                    <div class="text-caption text-secondary font-weight-medium mt-1">
                        On duty & available
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card elevation="1" rounded="lg" class="pa-4 bg-white">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-grey text-uppercase">Salary Payouts</span>
                        <v-avatar color="success" variant="tonal" size="36">
                            <v-icon icon="mdi-cash-check" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold text-grey-darken-3">₹ 3.2L</div>
                    <div class="text-caption text-grey font-weight-medium mt-1">
                        Processed this cycle
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card elevation="1" rounded="lg" class="pa-4 bg-white">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-grey text-uppercase">Spa Services</span>
                        <v-avatar color="accent" variant="tonal" size="36">
                            <v-icon icon="mdi-spa" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold text-grey-darken-3">15</div>
                    <div class="text-caption text-grey font-weight-medium mt-1">
                        Active treatments
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
