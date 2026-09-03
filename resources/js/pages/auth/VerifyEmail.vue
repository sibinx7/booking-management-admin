<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Alert, Button } from '@/components';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'Verify your email',
        description:
            'Please verify your email address by clicking on the link we sent to your inbox',
    },
});

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(send.url());
};
</script>

<template>
    <Head title="Email verification" />

    <Alert
        v-if="status === 'verification-link-sent'"
        type="success"
        class="mb-4"
        text="A new verification link has been sent to your email address."
    />

    <form @submit.prevent="submit">
        <Button
            type="submit"
            block
            size="large"
            :loading="form.processing"
            class="mb-4"
        >
            Resend verification email
        </Button>

        <div class="text-center">
            <Link
                :href="logout.url()"
                method="post"
                as="button"
                class="text-body-2 text-grey-darken-1 text-decoration-none"
            >
                Log out
            </Link>
        </div>
    </form>
</template>
