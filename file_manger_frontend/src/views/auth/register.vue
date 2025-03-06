<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                :class="errors.name ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
                ref="nameInput"
            />

            <InputError class="mt-2" :message="errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                :class="errors.email ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.email"
                required
                autocomplete="username"
                ref="emailInput"
            />

            <InputError class="mt-2" :message="errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="new-password"
                ref="passwordInput"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
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
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <router-link
                :to="{ name: 'login' }"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none"
            >
                Already registered?
            </router-link>

            <PrimaryButton
                class="ms-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Register
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
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    processing: false,
})

const errors = ref({
    name: '',
    email: '',
    password: '',
})

const nameInput = ref(null);
const emailInput = ref(null);
const passwordInput = ref(null);

const router = useRouter()

const submit = async () => {
    form.processing = true
    await api.post('register', form)
    .then((res) => {
        localStorage.setItem('token', res.data.token)
        localStorage.setItem('user', JSON.stringify(res.data.user))
        router.push({ name: 'verify-email' })
    }).catch((err) => {
        if(err.response.status == 400){
            if(err.response.data.errors.password){
                errors.value.password = err.response.data.errors.password[0]
            } 
            if(err.response.data.errors.email){
                errors.value.email = err.response.data.errors.email[0]
            }
            if(err.response.data.errors.name){
                errors.value.name = err.response.data.errors.name[0]
            }
            if(err.response.data.errors.password){
                passwordInput.value.focus()
            } 
            if(err.response.data.errors.email){
                emailInput.value.focus()
            }
            if(err.response.data.errors.name){
                nameInput.value.focus()
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
</script>
