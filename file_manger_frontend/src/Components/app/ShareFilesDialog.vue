<template>
    <Model :show="modelValue" max-width="2xl" @close="closeModal" @show="onShow">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Share Files
            </h2>
            
           <div class="mt-6">
                 <InputLabel for="Email" value="Email" class="sr-only"/>
                 <TextInput 
                        ref="emailInput"
                        type ="text" 
                        id="Email" 
                        v-model="form.email"
                        class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                        :class="emailError ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                        placeholder="Email"
                        @keyup.enter="shareFiles"
                        autocomplete
                        />

               <InputError class="mt-2" :message="emailError" />
            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                <PrimaryButton 
                    ref="createFolderButton"
                    @click="shareFiles" class="ml-3" 
                    :disable="form.processing" 
                    :class="{'opacity-25': form.processing}"
                    >
                Share
                </PrimaryButton>
            </div>
        </div> 
    </Model>
</template>

<script setup>
import Model from '@/Components/Modal.vue'
import InputLabel from '../InputLabel.vue';
import TextInput from '../TextInput.vue';
import InputError from '../InputError.vue';
import SecondaryButton from '../SecondaryButton.vue';
import PrimaryButton from '../PrimaryButton.vue';
import { nextTick, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/api';
import { emitter, Notification, ReloadFiles } from '@/event-bus';

const { modelValue, ids } = defineProps({
    modelValue: Boolean,
    ids: Array
})
const emit = defineEmits(['update:modelValue'])
const emailInput = ref(null)
const emailError = ref('')

const form = reactive({
    email: '',
})

const closeModal = () => {
    emit('update:modelValue')
}

const shareFiles =async () => {
    form.ids = ids
    await api.post('share-files', 
    form, 
    { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        emitter.emit(ReloadFiles)
        emitter.emit(Notification, {
            success: true,
            message: 'Files shared successfully'
        })
        form.email = ''
        emailError.value = ''
        closeModal()
    }).catch(err => {
         if(err.response.status == 400){
            if(err.response.data.message){   
                emailError.value = err.response.data.message
            }else{
                emailError.value = err.response.data.errors.email[0]
            }
            emailInput.value.focus()
         }else{
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
         }
         
    })
}


const onShow = () => {
    nextTick(() => {  
        emailInput.value.focus()
    })
}
</script>