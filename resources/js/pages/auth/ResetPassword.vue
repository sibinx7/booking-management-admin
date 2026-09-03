<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button, Input } from '@/components';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Reset password',
        description: 'Please enter your new password below',
    },
});

const props = defineProps<{
    token: string;
    email: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);

const submit = () => {
    form.post(update.url(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset password" />

    <form @submit.prevent="submit">
        <Input
            v-model="form.email"
            label="Email address"
            type="email"
            prepend-inner-icon="mdi-email-outline"
            :error-messages="form.errors.email"
            class="mb-3"
            autocomplete="username"
            required
            readonly
        />

        <Input
            v-model="form.password"
            label="New password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            prepend-inner-icon="mdi-lock-outline"
            :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showPassword = !showPassword"
            :error-messages="form.errors.password"
            class="mb-3"
            autocomplete="new-password"
            required
        />

        <Input
            v-model="form.password_confirmation"
            label="Confirm password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            prepend-inner-icon="mdi-lock-check-outline"
            :error-messages="form.errors.password_confirmation"
            class="mb-5"
            autocomplete="new-password"
            required
        />

        <Button
            type="submit"
            block
            size="large"
            :loading="form.processing"
        >
            Reset password
        </Button>
    </form>
</template>
