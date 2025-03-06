<template>

    <div 
    class="duration-150 transition flex items-center justify-between whitespace-wrap fixed bottom-[20px] left-[20px] w-[300px] p-4 text-white font-semibold text-md z-50 rounded"
    :class="[
        success ? 'bg-emerald-900' : 'bg-red-950',
        close ?'opacity-0 z-[-50] pointer-events-none' : 'opacity-100 z-50 ' ,
    ]"
    >   
        <span>{{ message }} </span>
        <button @click="closeNotification" 
        class="p-2 rounded cursor-pointer text-white  hover:text-gray-400 duration-150 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

</template>

<script setup>
import { emitter, Notification } from '@/event-bus';
import { ref } from 'vue';
const message = ref('')
const success = ref(true)

const close = ref(true)

const timer = ref(null)

const closeNotification = () => {
    close.value = true
    message.value = ''
    success.value = true
    clearTimeout(timer.value)
}

emitter.on(Notification , (data) => {
    message.value = data.message
    success.value = data.success
    close.value = false
    timer.value = setTimeout(() => {
        closeNotification()
    }, 5000)
})



</script>