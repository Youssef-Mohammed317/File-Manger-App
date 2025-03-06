
<template>
        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your
            email by entring the code we just emailed to you? If you didn't
            receive the email, we will gladly send you another.
        </div>

        <form>
            <div class="mt-4">
                <InputLabel
                    for="verifyCode"
                    value="Code"
                />

                <TextInput
                    id="verifyCode"
                    type="number"
                    class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none [appearance-number-field]  mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                    v-model="form.verify_code"
                    required
                    autofocus
                    autocomplete="verify_code"
                    ref="verifyCodeInput"
                    :class="verifyErrors ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    />

                <InputError
                    class="mt-2"
                    :message="verifyErrors"
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
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click.prevent="verify"
                    class="rounded-md text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                    Verify
                </PrimaryButton>
            </div>
        </form>
</template>
<script setup>
import { reactive, ref } from 'vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import api from '@/api';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useRouter } from 'vue-router';
import {  emitter, Notification } from '@/event-bus';
import InputError from '@/Components/InputError.vue';

const router = useRouter()

const form = reactive({
    email: JSON.parse(localStorage.getItem('user')).email,
    verify_code: '',
});

const verifyCodeInput = ref(null)
const verifyErrors = ref('')

const verify = async () => {
    await api.post('verify-email', form,{ headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        router.push({ name: 'dashboard', query: { parent_id: JSON.parse(localStorage.getItem('user')).root_id } }).then(() => {
            router.go(0)
            emitter.emit(Notification, {
                success: true,
                message: 'Email verified successfully'
            })
        })
    }).catch(err => {
        if(err.response.status == 400) {
            if(err.response.data.errors.verify_code){
                verifyErrors.value = err.response.data.errors.verify_code[0]
            }
            verifyCodeInput.value.focus()
        }else if(err.response.status == 404) {
            verifyErrors.value = err.response.data.message
            verifyCodeInput.value.focus()
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
            router.go({ name: 'login' })
        }
    })
};

const resend = async () => {
    await api.post('resend-verify-email', form,
        { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}
    )
    .then(res => {
        emitter.emit(Notification, {
            success: true,
            message: 'Verification email sent successfully'
        })
    }).catch(err => {
        window.location.href = '/login'
    })
};
</script>
