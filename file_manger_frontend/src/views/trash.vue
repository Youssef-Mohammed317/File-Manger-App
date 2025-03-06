<template>
    <div v-if="!loading">
        <nav class="flex items-center justify-end p-1 mb-3">
            <div class="flex justify-end items-center gap-2">
            <SecondaryButton @click="restoreSelected()" class="px-2 py-1">
                <span class="me-2 text-[10px]">Restore</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>

            </SecondaryButton>
            <PrimaryButton @click="deleteSelected()" class="px-2 py-1">
                <span class="me-2 text-[10px]">Delete</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </PrimaryButton>
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 border-b border-gray-300 shadow ">
                        <tr>
                            <th class="text-sm font-medium text-gray-900 ps-6 pl-4 py-4 text-left w-[30px] m-x-[30px]">
                                <input
                                    type="checkbox"
                                    class="cursor-pointer rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    v-model="allSelected"
                                    :checked="allSelected"
                                    @change="onSelectAll" 
                                    /> 
                            </th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Name
                            </th>
                            <th class="hidden lg:table-cell text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Owner
                            </th>
                            <th class="hidden lg:table-cell text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Deleted
                            </th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Size
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file of files" :key="file.id" 
                            @click="selected[file.id] = !selected[file.id];onSelect()"
                            class="tr-hover-action overflow-hidden border-b border-gray-200 shadow transition duration-300 ease-in-out  cursor-pointer"
                            :class="selected[file.id] ? 'bg-blue-50 hover:bg-blue-100':'bg-white hover:bg-gray-100'"
                        >
                            <td class="ps-4 pl-4 sm:ps-6 sm:pl-4 py-4 text-left w-[30px] m-x-[30px] whitespace-nowrap text-sm font-medium text-gray-900">
                                <input
                                        type="checkbox"
                                        class="cursor-pointer rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        @change="onSelect"
                                        v-model="selected[file.id]"
                                        :checked="selected[file.id]"
                                        />    
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900">
                                <div class="flex items-center gap-4">
                                    <FileIcon :file="file" />
                                    {{ (file.name.split(".")[0].length > 15 ? file.name.split(".")[0].slice(0,15) + '..': file.name.split(".")[0] )+ (file.name.split(".").length > 1 ? file.name.split(".")[1]:'') }} 
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{file.owner}}
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{file.deleted_at}}
                            </td>
                            <td class="last-cell relative px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="">{{ !file.is_folder ? file.size : '' }}</span>
                                <div class="absolute top-[50%] right-[10px] z-50 -translate-y-[50%] flex justify-center items-center gap-1">
                                    <button @click="restoreFile(file.id)" class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                        </svg>

                                    </button>
                                    <button  @click="deleteFile(file.id)" class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="!files.length" class="py-8 text-center text-lg text-gray-400">
                    There is no data in this folder
                </div>
        </div>
    </div>
   <div class="h-full w-full flex items-center justify-center"  v-if="loading">
        <Loader />
    </div>
</template>

<script setup >
import FileIcon from '@/Components/app/FileIcon.vue';
import api from '@/api';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { emitter, Notification, ReloadFiles, ShowDeleteFileConfirmation } from '@/event-bus';
import {  onMounted, reactive, ref, } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Loader from '@/Components/app/Loader.vue';

const route = useRoute()

const router = useRouter()

const loading = ref(true)

const allSelected = ref(false)

const selected = ref({})

const files = ref([])

const emit = defineEmits(['close'])

const onSelectAll = () => {
files.value.forEach(file => {
    selected.value[file.id] = allSelected.value
    if(!allSelected.value){
        delete selected.value[file.id]
    }
});
}

const onSelect = () => {
    allSelected.value = true
    files.value.forEach(file => {
        if(!selected.value[file.id]){
            allSelected.value = false
            delete selected.value[file.id]
        } 
    })
}

onMounted(() => {
  getFiles()
})


const restoreSelected = async () => {
    if(Object.keys(selected.value).length){
        restoreFromTrash(Object.keys(selected.value))
    }
}
const restoreFile = (id) => {
    restoreFromTrash([id])
}
const restoreFromTrash = async (ids) => {
    await api.post('restore-files', {
        ids: ids
    }, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        emitter.emit(ReloadFiles)
        emitter.emit(Notification, {
            success: true,
            message: 'Files restored successfully'
        })
    }).catch(err => {
    })
}

const deleteSelected = () => {
    if(Object.keys(selected.value).length){
        emitter.emit(ShowDeleteFileConfirmation,{
            ids: Object.keys(selected.value),
            trash: false
        })
    }
}
const deleteFile = (id) => {
    emitter.emit(ShowDeleteFileConfirmation,{
        ids: [id],
        trash: false
    })
}

const getFiles = async () => {
    loading.value = true
    await api.post('get-deleted-files', {
        parent_id: route.query.parent_id ? route.query.parent_id : JSON.parse(localStorage.getItem('user')).root_id
    }, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }})
    .then(res => {
        files.value = res.data.files
    }).catch(err => {
    }).finally(() => {
        loading.value = false
    })
}

</script>
<style scoped>
.tr-hover-action {
    .last-cell {
        & > div{
            transition: all 0.3s;
            opacity: 0;
        }
    }
}

.tr-hover-action:hover {
    .last-cell {
        & > span {
            opacity: 0;
        }
        & > div {
            opacity: 1;
        }
    }
}
</style>