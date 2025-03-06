<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                    :class="errors.current_password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    autocomplete="current-password"
                />

                <InputError
                    :message="errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                    :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    autocomplete="new-password"
                />

                <InputError :message="errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                    :class="errors.password ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                    autocomplete="new-password"
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="cursor-pointer">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
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

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
    processing: false,
});

const errors = ref({
    current_password: '',
    password: '',
});


const updatePassword = async () => {
    form.processing = true;
    await api.post( 'update-password',
    form, 
    { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then((res) => {
        emitter.emit(Notification, {
            success: true,
            message: 'Password updated successfully'
        })
        errors.value.current_password = ''
        errors.value.password = ''
    }).catch((err) => {
         if(err.status == 404){
             errors.value.current_password = err.response.data.message
             currentPasswordInput.value.focus()
         } else if(err.status == 422){
             if(err.response.data.errors.password){
                 errors.value.password = err.response.data.errors.password[0]
             }
             if(err.response.data.errors.current_password){
                 errors.value.current_password = err.response.data.errors.current_password[0]
             }
             currentPasswordInput.value.focus()
         }else{
             emitter.emit(Notification, {
                 success: false,
                 message: 'Something went wrong'
             })
         }
    }).finally(() => {
        form.processing = false
        form.current_password = ''
        form.password = ''
        form.password_confirmation = ''
    })
};
</script>