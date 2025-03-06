<template>
    <Model :show="modelValue" max-width="2xl" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Add to Favorite Files
            </h2>
            
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                <PrimaryButton 
                    ref="createFolderButton"
                    @click="addToMyFavoriteFromProps" class="ml-3" 
                    :disable="processing" 
                    :class="{'opacity-25': processing}"
                    >
                Add To Favorite
                </PrimaryButton>
            </div>
        </div> 
    </Model>
</template>

<script setup>
import Model from '@/Components/Modal.vue'
import SecondaryButton from '../SecondaryButton.vue';
import PrimaryButton from '../PrimaryButton.vue';
import { onMounted, ref } from 'vue';
import api from '@/api';
import { AddFavorite, emitter, Notification, ReloadFiles } from '@/event-bus';

const { modelValue, ids } = defineProps({
    modelValue: Boolean,
    ids: Array
})
const emit = defineEmits(['update:modelValue'])

const closeModal = () => {
    emit('update:modelValue')
}
const processing = ref(false)

const addToMyFavoriteFromProps = () => {
    addToMyFavorite(ids)
}

const addToMyFavorite = async (ids) => {
    processing.value = true
    await api.post('add-to-my-favorites', 
    {
        ids: ids
    }, 
    { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        emitter.emit(ReloadFiles)
        emitter.emit(Notification, {
            success: true,
            message: 'Files Added to Favorite successfully'
        })
        closeModal()
    }).catch(err => {
        emitter.emit(Notification, {
            success: false,
            message: 'Something went wrong'
        })
    }).finally(() => {
        processing.value = false
    })
}

onMounted(() => {
    emitter.on(AddFavorite, addToMyFavorite)
})

</script>