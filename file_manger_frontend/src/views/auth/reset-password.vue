<template>
    <form @submit.prevent>
        <div class="mt-4">
                <InputLabel
                    for="verifyCode"
                    value="Code"
                />

                <input
                    id="verifyCode"
                    type="number"
                    v-model="form.verify_code"
                    required
                    autofocus
                    class="[appearance-number-field] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                    :class="errors.verify_code ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : 'border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    ref="verifyCodeInput"
                />

                <InputError
                    class="mt-2"
                    :message="errors.verify_code"
                />
        </div>


        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : 'border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.password"
                required
                autocomplete="new-password"
                ref="passwordInput"
            />

            <InputError class="mt-2" :message="errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel
                for="password_confirmation"
                value="Confirm Password"
            />

            <TextInput
                id="password_confirmation"
                type="password"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : 'border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <SecondaryButton
                    @click.prevent="resend"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                Resend Verification Email
            </SecondaryButton>

            <PrimaryButton
                @click.prevent="reset"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Reset Password
            </PrimaryButton>
        </div>
    </form>
</template>
<script setup>

import api from '@/api';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { emitter, Notification } from '@/event-bus';
import router from '@/router';
import { reactive, ref } from 'vue';

const form = reactive({
    email: localStorage.getItem('email'),
    password: '',
    password_confirmation: '',
    processing: false,
    verify_code: ''
});
const errors = ref({
    email: '',
    password: '',
    verify_code: ''
})
const verifyCodeInput = ref(null)
const passwordInput = ref(null)
const reset = async () => {
    form.processing = true;
    await api.post('reset-password', form)
    .then((res) => {
        localStorage.removeItem('email')
        router.push({ name: 'login' }).then(() => {
            emitter.emit(Notification, {
                success: true,
                message: 'Password reset successfully'
            })
        })
    }).catch((err) => {
        if(err.response.status == 400){
            errors.value.password = err.response.data.errors.password[0]
            errors.value.verify_code = err.response.data.errors.verify_code[0]
            if(err.response.data.errors.password){
                passwordInput.value.focus()
            }
            if(err.response.data.errors.verify_code){
                verifyCodeInput.value.focus()
            }    
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
            router.go({ name: 'login' })
        }
    }).finally(() => {
        form.processing = false
        form.password = ''
        form.password_confirmation = ''
    });
};

const resend = async () => {
    form.processing = true;
    await api.post('forgot-password', form)
    .then(res => {
        emitter.emit(Notification, {
            success: true,
            message: 'Verification email sent successfully'
        })
    }).catch(err => {
        router.go({ name: 'login' })
    }).finally(() => {
        form.processing = false
    });
};
</script>
