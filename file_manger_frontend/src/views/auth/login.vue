<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none sm:text-sm"
                :class="errors.email ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : 'border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                ref="email"
            />

            <InputError class="mt-2" :message="errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                ref="password"
                id="password"
                type="password"
                v-model="form.password"
                required
                class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                autocomplete="current-password"
            />

            <InputError class="mt-2" :message="errors.password" />
        </div>

        <div class="mt-4 block">
            <label class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="ms-2 text-sm text-gray-600"
                    >Remember me</span
                >
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            <router-link
                :to="{ name: 'forgot-password' }"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none"
            >
                Forgot your password?
            </router-link>

            <PrimaryButton
                class="ms-4 cursor-pointer"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Log in
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { reactive, ref } from 'vue';
import api from '@/api';
import { RouterLink, useRouter } from 'vue-router';
import { emitter, Notification } from '@/event-bus';


const form = reactive({
    email: '',
    password: '',
    remember: false,
    processing: false,
});

const errors = ref({
    email: '',
    password: '',
});

const email = ref(null)
const password = ref(null)

const router = useRouter()

const submit = async () => {
    form.processing = true;
    await api.post('login', form)
    .then((res) => {
        localStorage.setItem('token', res.data.token)
        localStorage.setItem('user', JSON.stringify(res.data.user))
        if(res.data.user.verified) {
            router.push({ name: 'dashboard' , query: { parent_id: res.data.user.root_id } }).then(() => {
                router.go(0)
            })
        } else {
            router.push({ name: 'verify-email' }).then(() => {
                emitter.emit(Notification, {
                    success: true,
                    message: 'Email not verified'
                })
            })
        }
    }).catch((err) => {
         if(err.response.status == 404) {
             if(err.response.data.message){
                 errors.value.password = err.response.data.message
                password.value.focus()
             }
         }else if(err.response.status == 400) {
            if(err.response.data.errors.password){
                errors.value.password = err.response.data.errors.password[0]
            }
            if(err.response.data.errors.email){
                errors.value.email = err.response.data.errors.email[0]
            }
            email.value.focus()
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
    });
};
</script>

