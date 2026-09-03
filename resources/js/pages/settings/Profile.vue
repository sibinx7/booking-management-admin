<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Alert, Button, Heading, Input } from '@/components';
import { edit as editProfile } from '@/routes/profile';
import { send as sendVerification } from '@/routes/verification';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Profile settings',
                href: editProfile(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const form = useForm({
    name: user.value.name || '',
    email: user.value.email || '',
});

const submit = () => {
    form.patch('/settings/profile');
};

const verificationForm = useForm({});
const sendVerificationEmail = () => {
    verificationForm.post(sendVerification.url());
};
</script>

<template>
    <Head title="Profile settings" />

    <Heading
        title="Profile Information"
        description="Update your account's profile name and email address"
        variant="small"
    />

    <form @submit.prevent="submit" class="max-w-lg">
        <Input
            v-model="form.name"
            label="Name"
            prepend-inner-icon="mdi-account-outline"
            :error-messages="form.errors.name"
            class="mb-3"
            required
        />

        <Input
            v-model="form.email"
            label="Email address"
            type="email"
            prepend-inner-icon="mdi-email-outline"
            :error-messages="form.errors.email"
            class="mb-4"
            required
        />

        <div v-if="page.props.mustVerifyEmail && !user.email_verified_at" class="mb-4">
            <Alert
                type="warning"
                text="Your email address is unverified."
                class="mb-2"
            />
            <Button
                variant="text"
                size="small"
                :loading="verificationForm.processing"
                @click="sendVerificationEmail"
                class="px-0"
            >
                Click here to re-send the verification email.
            </Button>
        </div>

        <Button
            type="submit"
            :loading="form.processing"
        >
            Save Changes
        </Button>
    </form>
</template>
