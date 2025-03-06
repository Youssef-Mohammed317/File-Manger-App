<template>
    <Modal :show="show" max-width="md" @close="close">
        <div class="p-6"> 
            <h2 class="text-2xl mb-2 text-red-600 font-semibold">Error</h2>
            <p class="text-sm text-gray-600">{{ message }}</p>
            <div class="flex justify-end mt-6">
                <PrimaryButton @click="close">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup >
import { onMounted, ref } from 'vue';
import Modal from '../Modal.vue';
import PrimaryButton from '../PrimaryButton.vue';
import { emitter, ShowErrorDialog } from '@/event-bus';

const show = ref(false)
const message = ref('')

const emit = defineEmits(['close'])

const close = () => {
    show.value = false
    message.value = ''
}

onMounted(() => {
    emitter.on(ShowErrorDialog, (msg) => {
        show.value = true
        message.value = msg
    })
})
</script>