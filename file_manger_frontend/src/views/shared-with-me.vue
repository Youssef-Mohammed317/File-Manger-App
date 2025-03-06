<template>
    <div v-if="!loading && !loadingAncestors">
        <nav class="p-1 mb-3">

            <div class="flex justify-end items-center gap-2 mb-3">
                <SecondaryButton @click="addToMyFavoritesSelected()" class="px-2 py-1">
              <span class="me-2 text-[10px] hidden md:inline">Add to favorites</span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                </svg>
            </SecondaryButton>
            <SecondaryButton @click="shareSelected()" class="px-2 py-1">
                <span class="me-2 text-[10px] hidden md:inline">Share</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
            </svg>


            </SecondaryButton>
            <SecondaryButton @click="downloadSelected()" class="px-2 py-1">
                <span class="me-2 text-[10px] hidden md:inline">Download</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>

            </SecondaryButton>
            <PrimaryButton @click="deleteSelected()" class="px-2 py-1">
                <span class="me-2 text-[10px] hidden md:inline">Remove From Shared</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </PrimaryButton>
            </div>
            <ol class="inline-flex items-center justify-start">
                <li class="inline-flex items-center me-1">
                    <router-link :to="{ name: 'shared-with-me' }" class="gap-2 inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                         <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z" clip-rule="evenodd" />
                        </svg>

                        <span class="whitespace-nowrap ml-1">Shared with me</span>
                    </router-link>
                </li>
                <li v-for="ans,index of ancestors" :key="ans.id" class="inline-flex items-center">
                    <div class="flex items-center" v-if="index != 0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                        <router-link :to="{ name: 'shared-with-me', query: { parent_id: ans.id }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            {{ ans.name }}
                        </router-link>
                    </div>
                </li>
            </ol>
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
                                Last Modified
                            </th>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Size
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file of files" :key="file.id" 
                            @dblclick="open(file)" 
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
                                {{ file.owner.split(" ")[0] }}
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{file.updated_at}}
                            </td>
                            <td class="last-cell relative px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="">{{ !file.is_folder ? file.size : '' }}</span>
                                <div class="absolute top-[50%] right-[10px]z-50 -translate-y-[50%] flex justify-center items-center gap-1">
                                    <button @click="downloadFile(file)" class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </button>
                                    <button @click="shareFile(file.id)" class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                                        </svg>
                                    </button>
                                    <button v-if="!file.is_starred" @click="addToFavorite(file.id)" class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                    </button>
                                    <button v-if="file.is_starred" @click="removeFavorite(file.id)"  class="bg-gray-200 cursor-pointer hover:bg-gray-300 transition e duration-300 ease-in-out rounded-full px-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
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
   <div class="h-full w-full flex items-center justify-center" v-if="loading || loadingAncestors" >
        <Loader />
    </div>
</template>

<script setup >
import FileIcon from '@/Components/app/FileIcon.vue';
import api from '@/api';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { AddFavorite, AddToMyFavorites, DeleteFilesCompleted, DownloadFiles, emitter, OpenFile, ReloadFiles, RemoveFavorites, ShareFiles, ShowDeleteFileConfirmation } from '@/event-bus';
import {  onMounted, onUpdated, reactive, ref, } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Loader from '@/Components/app/Loader.vue';
import { isAudio, isImage, isPdf, isVideo } from '@/Helper/file-helper';

const route = useRoute()

const router = useRouter()

const loading = ref(true)

const loadingAncestors = ref(true)

const allSelected = ref(false)

const selected = ref({})

const files = ref([])

const ancestors = ref([])

const emit = defineEmits(['close'])

const open = (file) => {
    if(!file.is_folder){
        if(isImage(file) || isPdf(file) || isVideo(file) || isAudio(file)){
            emitter.emit(OpenFile, file)
        } else {
            downloadFile(file)
        }
    } else {
        router.push({
            name: 'shared-with-me',
            query: {
                parent_id: file.id,
                created_by: file.created_by
            }
        }).then(() => {  
            emitter.emit(ReloadFiles)
        })
    }
}


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
    getAncestors()
    getSharedWithMeFiles()
})

const downloadSelected = () => {
  if(Object.keys(selected.value).length){
    let name = 'download'
    if(Object.keys(selected.value).length == 1){
            files.value.forEach(file => {
                if(file.id == Object.keys(selected.value)[0]){
                    name = file.name
                }
            })
        }
     emitter.emit(DownloadFiles, {ids:Object.keys(selected.value), name: name})
  }
}

const downloadFile = (file) => {
    emitter.emit(DownloadFiles, {ids:[file.id], name: file.name})
}

const getSharedWithMeFiles = async () => {
    loading.value = true
    await api.post('get-shared-files-with-me', {
        parent_id: route.query.parent_id ? route.query.parent_id : '',
        created_by: route.query.created_by ? route.query.created_by : ''
    },
    { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}
    ).then(res => {
        files.value = res.data.files
    }).catch(err => {
    }).finally(() => {
        loading.value = false
    })
}

const shareSelected = () => {
  if(Object.keys(selected.value).length){
      emitter.emit(ShareFiles, Object.keys(selected.value))
  }
}

const shareFile = (id) => {
    emitter.emit(ShareFiles, [id])
}

const addToMyFavoritesSelected = () => {
  if(Object.keys(selected.value).length){
      emitter.emit(AddToMyFavorites, Object.keys(selected.value))
  }
}
const removeFavorite = (id) => {
    emitter.emit(RemoveFavorites, [id])
}

const addToFavorite = (id) => {
    emitter.emit(AddFavorite,[id])
}

const deleteSelected = () => {
    if(Object.keys(selected.value).length){
        deleteFromShareWithMe(Object.keys(selected.value))
    }
}
const deleteFile = (id) => {
    deleteFromShareWithMe([id])
}
const deleteFromShareWithMe = async (ids) => {
    await api.post('delete-share-with-me',
    {
        ids: ids
    },
    { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}).then(res => {
        selected.value = {}
        allSelected.value = false
        emitter.emit(ReloadFiles)
    }).catch(err => {
    })
}
const getAncestors = async () => {
    loadingAncestors.value = true
    await api.post('get-ancestors', {
        parent_id: route.query.parent_id ? route.query.parent_id : ''
        }, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}).then(res => {
        ancestors.value = res.data.ancestors
    }).catch(err => {
        //  console.log(err)
    }).finally(() => {
        loadingAncestors.value = false
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