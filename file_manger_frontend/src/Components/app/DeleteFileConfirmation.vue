<template>
    <Modal :show="show" max-width="md" @close="close" @keyup.enter="deleteFiles">
        <div class="p-6"> 
            <h2 class="text-2xl mb-2 text-gray-800 font-semibold">Please Confirm</h2>
            <p class="text-sm text-gray-600">
                {{ 
                trash ? 
                'Are you sure you want to delete these files?' 
                :'Are you sure you want to delete forever these files?'
                }}
             </p>
            <div class="flex justify-end mt-6 gap-4">
                <SecondaryButton @click="close">Cancel</SecondaryButton>
                <DangerButton class="bg-red-700 hover:bg-red-600 cursor-pointer" @click="deleteFiles" >Confirm</DangerButton>
            </div>
        </div>
    </Modal>
</template>

<script setup >
import { onMounted, reactive, ref } from 'vue';
import Modal from '../Modal.vue';
import { DeleteFilesCompleted, emitter, Notification, ReloadFiles, ShowDeleteFileConfirmation } from '@/event-bus';
import DangerButton from '../DangerButton.vue';
import SecondaryButton from '../SecondaryButton.vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/api';

const show = ref(false)

const emit = defineEmits(['close'])

const route = useRoute()

const close = () => {
    show.value = false
}
const ids = ref({})
const trash = ref(true)

onMounted(() => {
    emitter.on(ShowDeleteFileConfirmation, (data) => {
        show.value = true
        ids.value = data.ids
        trash.value = data.trash
    })
})

const deleteFiles = () => {
    const form = reactive({
        parent_id: route.query.parent_id ? route.query.parent_id : JSON.parse(localStorage.getItem('user')).root_id,
        ids: ids.value,
        processing: true
    })
    api.post(trash.value ? 'trash-files' : 'delete-files', form, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        // console.log(res)
        show.value = false
        emitter.emit(DeleteFilesCompleted)
        emitter.emit(ReloadFiles)

        emitter.emit(Notification, {
            success: true,
            message:  trash.value ? 'Files moved to trash successfully' : 'Files deleted successfully'
        })
    })
    .catch(err => {
        // console.log(err)
    })
    .finally(() => {
        form.processing = false


    })
}
</script>