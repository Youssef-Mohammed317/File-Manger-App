<template>
    <Model :show="modelValue" max-width="2xl" @close="closeModal" @show="onShow">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Create New Folder
            </h2>
            
           <div class="mt-6">
                 <InputLabel for="folderName" value="Folder Name" class="sr-only"/>
                 <TextInput 
                        ref="folderName"
                        type ="text" 
                        id="folderName" 
                        v-model="form.name"
                        class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none f sm:text-sm"
                        :class="nameErrors ? 'text-red-500 border-red-500 ring-red-500 ring-1 focus:ring-red-500 focus:border-red-500 ' : ' border-slate-300 focus:border-sky-500 focus:ring-sky-500'"
                        placeholder="Folder Name"
                        @keyup.enter="createFolder"
                        />

               <InputError class="mt-2" :message="nameErrors" />
            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                <PrimaryButton 
                    ref="createFolderButton"
                    @click="createFolder" class="ml-3" 
                    :disable="form.processing" 
                    :class="{'opacity-25': form.processing}"
                    >
                Create
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
import { useRoute } from 'vue-router';
import api from '@/api';
import { emitter, Notification, ReloadFiles } from '@/event-bus';

const { modelValue } = defineProps({
    modelValue: Boolean
})
const emit = defineEmits(['update:modelValue'])
const route = useRoute()
const nameErrors = ref(null)
const form = reactive({
    name: '',
    parent_id: '',
})

const folderName = ref(null)

const closeModal = () => {
    emit('update:modelValue')
}

const createFolder = async () => {
    form.parent_id = route.query.parent_id ? route.query.parent_id : JSON.parse(localStorage.getItem('user')).root_id 
    await api.post('create-folder', form, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        closeModal()
        emitter.emit(ReloadFiles)
        emitter.emit(Notification,{
            success: true,
            message: 'Folder ' + form.name + ' created successfully'
        })
        form.name = ''
        nameErrors.value = ''
    })
    .catch(err => {
        if(err.response.data.message){
            nameErrors.value = err.response.data.message
            folderName.value.focus()
        } else if(err.response.data.errors.name){
            nameErrors.value = err.response.data.errors.name[0]
            folderName.value.focus()
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
        }
    })
}


const onShow = () => {
    nextTick(() => {  
        folderName.value.focus()
    })
}
</script>