<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Alert, Button, Input } from '@/components';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Forgot password',
        description:
            'Enter your email address and we will send you a password reset link',
    },
});

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(email.url());
};
</script>

<template>
    <Head title="Forgot password" />

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
            class="mb-4"
            autocomplete="username"
            required
        />

        <Button
            type="submit"
            block
            size="large"
            :loading="form.processing"
            class="mb-4"
        >
            Email password reset link
        </Button>

        <div class="text-center">
            <span class="text-body-2 text-grey-darken-1 mr-1">Or return to</span>
            <Link
                :href="login()"
                class="text-body-2 text-primary font-weight-medium text-decoration-none"
            >
                Log in
            </Link>
        </div>
    </form>
</template>
