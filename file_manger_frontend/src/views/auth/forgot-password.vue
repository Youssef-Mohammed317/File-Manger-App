
<template>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email
        address and we will email you a password reset link that will allow
        you to choose a new one.
    </div>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                ref="emailInput"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                :class="emailErrors ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : 'border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
            />

            <InputError class="mt-2" :message="emailErrors" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <PrimaryButton  
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Send Email Password Reset Code
            </PrimaryButton>
        </div>

    </form>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { reactive, ref } from 'vue';
import api from '@/api';
import { useRouter } from 'vue-router';
import { emitter, Notification } from '@/event-bus';

const form = reactive({
    email: '',
    processing: false,
});

const emailErrors = ref('');
const emailInput = ref(null);
const router = useRouter()
const submit = async () => {
    form.processing = true;
    await api.post('forgot-password', form)
    .then((res) => {
        localStorage.setItem('email', form.email)
        router.push({ name: 'reset-password' })
    }).catch((err) => {
         if(err.response.data.errors.email){
            emailErrors.value = err.response.data.errors.email[0]
            emailInput.value.focus()
         }else{

             emitter.emit(Notification, {
                 success: false,
                 message: 'Something went wrong'
                })
                router.go({ name: 'login' })
            }
    }).finally(() => {
        form.processing = false
    });
};
</script>

