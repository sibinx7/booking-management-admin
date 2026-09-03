<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button, Heading, Input } from '@/components';
import { edit as editSecurity } from '@/routes/security';

defineProps<{
    passwordRules?: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Security settings',
                href: editSecurity(),
            },
        ],
    },
});

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);

const submit = () => {
    form.put('/user/password', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Security settings" />

    <Heading
        title="Update Password"
        description="Ensure your account is using a long, random password to stay secure"
        variant="small"
    />

    <form @submit.prevent="submit" class="max-w-lg">
        <Input
            v-model="form.current_password"
            label="Current Password"
            :type="showCurrentPassword ? 'text' : 'password'"
            prepend-inner-icon="mdi-lock-outline"
            :append-inner-icon="showCurrentPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showCurrentPassword = !showCurrentPassword"
            :error-messages="form.errors.current_password"
            class="mb-3"
            autocomplete="current-password"
            required
        />

        <Input
            v-model="form.password"
            label="New Password"
            :type="showNewPassword ? 'text' : 'password'"
            prepend-inner-icon="mdi-lock-reset"
            :append-inner-icon="showNewPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showNewPassword = !showNewPassword"
            :error-messages="form.errors.password"
            class="mb-3"
            autocomplete="new-password"
            required
        />

        <Input
            v-model="form.password_confirmation"
            label="Confirm Password"
            :type="showNewPassword ? 'text' : 'password'"
            prepend-inner-icon="mdi-lock-check-outline"
            :error-messages="form.errors.password_confirmation"
            class="mb-4"
            autocomplete="new-password"
            required
        />

        <Button
            type="submit"
            :loading="form.processing"
        >
            Update Password
        </Button>
    </form>
</template>
