<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
        icon: 'mdi-account-outline',
    },
    {
        title: 'Security',
        href: editSecurity(),
        icon: 'mdi-shield-lock-outline',
    },
    {
        title: 'Appearance',
        href: editAppearance(),
        icon: 'mdi-palette-outline',
    },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-h5 font-weight-bold text-grey-darken-3 mb-1">Settings</h1>
            <p class="text-body-2 text-grey-darken-1 mb-0">Manage your profile and account settings</p>
        </div>

        <v-divider class="mb-6" />

        <v-row>
            <v-col cols="12" md="3">
                <v-list density="comfortable" nav class="pa-0 bg-transparent">
                    <v-list-item
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        :to="toUrl(item.href)"
                        :active="isCurrentOrParentUrl(item.href)"
                        color="primary"
                        rounded="lg"
                        class="mb-1"
                    >
                        <template #prepend>
                            <v-icon :icon="item.icon as string" class="mr-2" />
                        </template>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-col>

            <v-col cols="12" md="9">
                <v-card elevation="1" rounded="lg" class="pa-6 bg-white">
                    <slot />
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
