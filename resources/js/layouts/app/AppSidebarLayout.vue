<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { dashboard, logout } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const drawer = ref(true);
const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <v-app class="bg-grey-lighten-4">
        <!-- Navigation Drawer -->
        <v-navigation-drawer v-model="drawer" elevation="1" color="surface">
            <div class="pa-4 d-flex align-center">
                <v-avatar color="primary" size="40" class="mr-3 elevation-1">
                    <v-icon icon="mdi-spa" color="white" size="24" />
                </v-avatar>
                <div>
                    <div class="text-subtitle-1 font-weight-bold text-grey-darken-3 leading-tight">
                        Spa Admin
                    </div>
                    <div class="text-caption text-grey-darken-1">Management Portal</div>
                </div>
            </div>

            <v-divider />

            <v-list density="comfortable" nav class="px-3 py-2">
                <v-list-item
                    prepend-icon="mdi-view-dashboard-outline"
                    title="Dashboard"
                    :to="dashboard.url()"
                    link
                    color="primary"
                    rounded="lg"
                />

                <v-list-subheader class="font-weight-bold text-uppercase text-caption text-grey">
                    Operations
                </v-list-subheader>

                <v-list-item
                    prepend-icon="mdi-account-group-outline"
                    title="Employees"
                    link
                    color="primary"
                    rounded="lg"
                />

                <v-list-item
                    prepend-icon="mdi-account-heart-outline"
                    title="Therapists"
                    link
                    color="primary"
                    rounded="lg"
                />

                <v-list-item
                    prepend-icon="mdi-cash-multiple"
                    title="Payslips & Salary"
                    link
                    color="primary"
                    rounded="lg"
                />

                <v-list-subheader class="font-weight-bold text-uppercase text-caption text-grey">
                    Account
                </v-list-subheader>

                <v-list-item
                    prepend-icon="mdi-cog-outline"
                    title="Settings"
                    :to="editProfile.url()"
                    link
                    color="primary"
                    rounded="lg"
                />
            </v-list>

            <template #append>
                <div class="pa-3">
                    <v-btn
                        block
                        variant="tonal"
                        color="error"
                        prepend-icon="mdi-logout"
                        :href="logout.url()"
                        method="post"
                        as="button"
                        class="text-capitalize"
                    >
                        Log out
                    </v-btn>
                </div>
            </template>
        </v-navigation-drawer>

        <!-- App Bar -->
        <v-app-bar elevation="0" color="surface" class="border-b">
            <v-app-bar-nav-icon @click="drawer = !drawer" />

            <v-app-bar-title class="text-subtitle-1 font-weight-medium">
                <template v-if="breadcrumbs.length">
                    <span v-for="(crumb, idx) in breadcrumbs" :key="idx">
                        <span v-if="idx > 0" class="mx-2 text-grey">/</span>
                        <span :class="{ 'text-grey': idx < breadcrumbs.length - 1, 'font-weight-bold': idx === breadcrumbs.length - 1 }">
                            {{ crumb.title }}
                        </span>
                    </span>
                </template>
                <template v-else>
                    Spa Management
                </template>
            </v-app-bar-title>

            <v-spacer />

            <!-- User Menu -->
            <v-menu location="bottom end">
                <template #activator="{ props: menuProps }">
                    <v-btn v-bind="menuProps" variant="text" class="text-none px-2">
                        <v-avatar color="primary" size="32" class="mr-2">
                            <span class="text-caption text-white font-weight-bold">
                                {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                            </span>
                        </v-avatar>
                        <span class="text-body-2 font-weight-medium d-none d-sm-inline">{{ user?.name || 'Account' }}</span>
                        <v-icon icon="mdi-chevron-down" size="18" class="ml-1" />
                    </v-btn>
                </template>

                <v-list density="compact" min-width="180" rounded="lg" elevation="3">
                    <v-list-item :to="editProfile.url()" prepend-icon="mdi-account-circle-outline" title="Profile Settings" />
                    <v-divider class="my-1" />
                    <v-list-item :href="logout.url()" method="post" as="button" prepend-icon="mdi-logout" title="Log out" base-color="error" />
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Main Content -->
        <v-main>
            <v-container fluid class="pa-6">
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>
