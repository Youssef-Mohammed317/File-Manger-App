<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="cursor-pointer">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                        :class="passwordError ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="passwordError" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal" class="cursor-pointer">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3 cursor-pointer"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<script setup>
import api from '@/api';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { emitter, Notification } from '@/event-bus';
import { nextTick, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const passwordError = ref(null);
const form = reactive({
    password: '',
    processing: false,
});
const router = useRouter();

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = async () => {
    form.processing = true;
   await api.post('delete-user', form, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        emitter.emit(Notification, {
            success: true,
            message: 'Account deleted successfully'
        })
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        router.push({ name: 'login' }).then(() => {
            emitter.emit(Notification, {
                success: true,
                message: 'Account deleted successfully'
            })
        })
   }).catch(err => {
        console.log(err)
        if(err.status == 400){
            if(err.response.data.errors.password){
                passwordError.value = err.response.data.errors.password[0]
                passwordInput.value.focus()
            }
        } else if(err.status == 404){ 
            if(err.response.data.message){
                passwordError.value = err.response.data.message
                passwordInput.value.focus()
            }
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
        }

   }).finally(() => {
       form.processing = false
       form.password = ''
   })
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};
</script>
