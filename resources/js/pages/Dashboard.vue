<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card, Heading } from '@/components';
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
        <Heading
            title="Spa Overview"
            description="Welcome to your spa management control center"
        />

        <v-row>
            <v-col cols="12" sm="6" md="3">
                <Card elevation="1" class="pa-4">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-medium-emphasis text-uppercase">Employees</span>
                        <v-avatar color="primary" variant="tonal" size="36">
                            <v-icon icon="mdi-account-group-outline" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold">12</div>
                    <div class="text-caption text-success font-weight-medium mt-1">
                        Active staff members
                    </div>
                </Card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <Card elevation="1" class="pa-4">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-medium-emphasis text-uppercase">Therapists</span>
                        <v-avatar color="secondary" variant="tonal" size="36">
                            <v-icon icon="mdi-account-heart-outline" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold">8</div>
                    <div class="text-caption text-secondary font-weight-medium mt-1">
                        On duty & available
                    </div>
                </Card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <Card elevation="1" class="pa-4">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-medium-emphasis text-uppercase">Salary Payouts</span>
                        <v-avatar color="success" variant="tonal" size="36">
                            <v-icon icon="mdi-cash-check" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold">₹ 3.2L</div>
                    <div class="text-caption text-medium-emphasis font-weight-medium mt-1">
                        Processed this cycle
                    </div>
                </Card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <Card elevation="1" class="pa-4">
                    <div class="d-flex align-center justify-space-between mb-2">
                        <span class="text-caption font-weight-bold text-medium-emphasis text-uppercase">Spa Services</span>
                        <v-avatar color="accent" variant="tonal" size="36">
                            <v-icon icon="mdi-spa" size="20" />
                        </v-avatar>
                    </div>
                    <div class="text-h4 font-weight-bold">15</div>
                    <div class="text-caption text-medium-emphasis font-weight-medium mt-1">
                        Active treatments
                    </div>
                </Card>
            </v-col>
        </v-row>
    </div>
</template>
