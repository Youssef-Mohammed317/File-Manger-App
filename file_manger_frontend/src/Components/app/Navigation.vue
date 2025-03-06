<template>
    <nav style="transition: all 0.3s;" class="shadow transition duration-300 ease-in-out" :class="`${isMobile ? 'w-[68px]' : 'w-[200px]'}`">   
        <router-link 
            :to="{ name: 'dashboard', query: { parent_id: parent_id}}"
            @click="emitter.emit(ReloadFiles)"
            class="h-[80px] flex items-center gap-3 px-3">
                <div class="h-9 w-9">
                    <ApplicationLogo class="block h-9 w-9 fill-current text-gray-800"/>
                </div>
                <span v-if="!isMobile" class="whitespace-nowrap overflow-hidden">LaraStorage</span>
        </router-link>

        <div class="px-3">
            <CreateNewDropdown :isMobile="isMobile" />

            <div class="py-3">
                <router-link 
                    class="gap-2 flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                    :to="{ name: 'dashboard' , query: { parent_id: parent_id}}"
                    @click="emitter.emit(ReloadFiles)"
                    active-class="text-indigo-700 bg-indigo-100 hover:text-indigo-700 hover:bg-indigo-100"
                    >
                    <div class="h-5 w-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span v-if="!isMobile"  class="whitespace-nowrap overflow-hidden">My Files</span>
                </router-link>
                <router-link 
                    :to="{ name: 'my-favorites' }"
                    @click="emitter.emit(ReloadFiles)"
                    active-class="text-indigo-700 bg-indigo-100 hover:text-indigo-700 hover:bg-indigo-100"

                    class="gap-2 flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                >
              
                    <div class="h-5 w-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span v-if="!isMobile"  class="whitespace-nowrap overflow-hidden">My Favorites</span>
                </router-link>
                <router-link 
                    :to="{ name: 'shared-with-me' }"
                    @click="emitter.emit(ReloadFiles)"
                    active-class="text-indigo-700 bg-indigo-100 hover:text-indigo-700 hover:bg-indigo-100"

                    class="gap-2 flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                >
                    <div class="h-5 w-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span v-if="!isMobile"  class="whitespace-nowrap overflow-hidden">Shared with me</span>
                </router-link>
                <router-link 
                :to="{ name: 'shared-by-me' }"
                @click="emitter.emit(ReloadFiles)"
                active-class="text-indigo-700 bg-indigo-100 hover:text-indigo-700 hover:bg-indigo-100"

                    class="gap-2 flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                >
                    <div class="h-5 w-5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                    </div>
                    <span v-if="!isMobile"  class="whitespace-nowrap overflow-hidden">Shared by me</span>
                </router-link>
                <router-link 
                    :to="{ name: 'trash' }"
                    @click="emitter.emit(ReloadFiles)"
                    class="gap-2 flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                    active-class="text-indigo-700 bg-indigo-100 hover:text-indigo-700 hover:bg-indigo-100"
                >
                    <div class="h-5 w-5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>
                    <span v-if="!isMobile"  class="whitespace-nowrap overflow-hidden">Trash</span>
                </router-link>
            </div>
        </div>
    </nav>
</template>

<script setup>

import ApplicationLogo from '@/Components/ApplicationLogo.vue';

import CreateNewDropdown from '@/Components/app/CreateNewDropdown.vue';
import { emitter, ReloadFiles, SmallScreen } from '@/event-bus';
import { onMounted, ref } from 'vue';
const parent_id = ref('')

const { isMobile } = defineProps({
    isMobile: Boolean
})

onMounted(() => {
    if(localStorage.getItem('user')) {
        parent_id.value = JSON.parse(localStorage.getItem('user')).root_id
    }
})


</script>