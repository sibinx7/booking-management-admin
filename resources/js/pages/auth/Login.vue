<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Alert, Button, Checkbox, Input } from '@/components';
import { login } from '@/routes';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Sign in to Spa Admin',
        description: 'Enter your email and password to access your dashboard',
    },
});

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(login.url(), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <Alert
        v-if="status"
        type="success"
        class="mb-4"
        :text="status"
    />

    <form @submit.prevent="submit">
        <Input
            v-model="form.email"
            label="Email address"
            type="email"
            placeholder="name@example.com"
            prepend-inner-icon="mdi-email-outline"
            :error-messages="form.errors.email"
            class="mb-3"
            autocomplete="username"
            required
        />

        <Input
            v-model="form.password"
            label="Password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            prepend-inner-icon="mdi-lock-outline"
            :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showPassword = !showPassword"
            :error-messages="form.errors.password"
            class="mb-1"
            autocomplete="current-password"
            required
        />

        <div class="d-flex align-center justify-space-between mb-6">
            <Checkbox
                v-model="form.remember"
                label="Remember me"
            />

            <Link
                :href="request()"
                class="text-caption text-primary font-weight-medium text-decoration-none"
            >
                Forgot password?
            </Link>
        </div>

        <Button
            type="submit"
            block
            size="large"
            :loading="form.processing"
        >
            Log in
        </Button>
    </form>
</template>
