<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
        >
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

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                    :class="errors.email ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    ref="emailInput"
                />

                <InputError class="mt-2" :message="errors.email" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="cursor-pointer">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>
<script setup>
import api from '@/api';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { emitter, Notification } from '@/event-bus';
import { reactive, ref } from 'vue';

const user = JSON.parse(localStorage.getItem('user'));

const form = reactive({
    name: user.name,
    email: user.email,
    processing: false
});
const emailInput = ref(null)
const nameInput = ref(null)
const errors = ref({
    name: '',
    email: ''
})
const submit = async () => {
    form.processing = true;
    await api.post('update-info', form , { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then((res) => {
        localStorage.setItem('user', JSON.stringify(res.data.user))
        emitter.emit('nameChanged')
        emitter.emit(Notification, {
            success: true,
            message: 'Profile updated successfully'
        })
        errors.value.name = ''
        errors.value.email = ''
    }).catch((err) => {
        if(err.response.status == 422){
            errors.value.name = err.response.data.errors.name[0]
            errors.value.email = err.response.data.errors.email[0]
            if(err.response.data.errors.name){
                nameInput.value.focus()
            }
            if(err.response.data.errors.email){
                emailInput.value.focus()
            }
        }
        else if(err.status == 404){
            if(err.response.data.message){
                errors.value.email = err.response.data.message
            }
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
        }
    }).finally(() => {
        form.processing = false
    });
};

</script>