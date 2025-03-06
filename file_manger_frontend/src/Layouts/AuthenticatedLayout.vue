<template>
    <div class="h-screen bg-gray-50 flex w-full">
        <Navigation :isMobile="isMobile" />
        <main 
            @drop.prevent="handelDrop" 
            @dragover.prevent="handleDragOver" 
            @dragleave.prevent="handleDragLeave" 
            class="flex flex-col flex-1 px-4 overflow-hidden relative"
            :class="{ 'bg-gray-200 text-gray-500 text-md flex items-center justify-center border-dashed border-2 border-gray-400 h-full': dragOver }"
            >
            <template v-if="dragOver" >
                    Drop files to upload
            </template>
            <template v-else>
                <button class="absolute top-[5px] left-0 cursor-pointer px-1 py-3 bg-indigo-100 hover:bg-indigo-200 rounded-tr-lg rounded-br-lg font-semibold focus:outline-none transition duration-300" @click="isMobile = !isMobile">
                    <svg v-if="isMobile" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                    <svg v-if="!isMobile" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <div class="flex items-center justify-between w-full ms-5">
                    <SearchForm />
                    <UserSettingsDropdown />
                </div>
                <div class="flex-1 flex flex-col overflow-hidden">
                    <slot />
                </div>
            </template>
        </main>
    </div>
    <ErrorDialog />
    <DeleteFileConfirmation />
    <ShareFilesDialog v-model="shareFilesDialog" :ids="sharedIds" />
    <AddToFavoriteFilesDialog v-model="addToFavoriteDialog" :ids="favoriteIds" />
    <UploadLoading :-files-numbers="fileUploadForm.files.length" v-if="fileUploadForm.processing" />
    <OpenFileDialog v-model="showOpenFileDialog" :file="file" />
</template>

<script setup>
import DeleteFileConfirmation from '@/Components/app/DeleteFileConfirmation.vue'
import ErrorDialog from '@/Components/app/ErrorDialog.vue'
import api from '@/api'
import Navigation from '@/Components/app/Navigation.vue'
import SearchForm from '@/Components/app/SearchForm.vue'
import UserSettingsDropdown from '@/Components/app/UserSettingsDropdown.vue'
import { AddToMyFavorites, DownloadFiles, emitter, FileUploadStarted, Notification, OpenFile, ReloadFiles, RemoveFavorites, ShareFiles, showErrorDialogFn } from '@/event-bus'
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import ShareFilesDialog from '@/Components/app/ShareFilesDialog.vue'
import UploadLoading from '@/Components/app/UploadLoading.vue'
import AddToFavoriteFilesDialog from '@/Components/app/AddToFavoriteFilesDialog.vue'
import OpenFileDialog from '@/Components/app/OpenFileDialog.vue'

defineOptions({ inheritAttrs: false });
const route = useRoute()
const isMobile = ref(false)
const dragOver = ref(false)
const shareFilesDialog = ref(false)
const addToFavoriteDialog = ref(false)
const showOpenFileDialog = ref(false)
const file = ref({})
const sharedIds = ref([])
const favoriteIds = ref([])
const fileUploadForm = reactive({
    files:[],
    relative_paths: [],
    parent_id: null,
    processing: false
})



const handelDrop = (ev) => {
    dragOver.value = false
    const files = ev.dataTransfer.files
    if(!files.length) {
        return
    }
    uploadFiles(files)
}
const handleDragOver = () => {
    dragOver.value = true
}
const handleDragLeave = () => {
    dragOver.value = false
}
const uploadFiles = async (files) => {
    fileUploadForm.parent_id = route.query.parent_id ? route.query.parent_id : JSON.parse(localStorage.getItem('user')).root_id
    fileUploadForm.files = files
    fileUploadForm.relative_paths = Array.from(files).map(file => file.webkitRelativePath)
    fileUploadForm.processing = true
    await api.post('upload-files', 
        fileUploadForm, 
        { headers: { Authorization: 'Bearer ' + localStorage.getItem('token')
     }}).then((res) => {
        emitter.emit(ReloadFiles)
        emitter.emit(Notification, {
            success: true,
            message: fileUploadForm.files[0].webkitRelativePath ? 'Folder Uploaded Successfully' : 'Files Uploaded Successfully'
        })
    }).catch(err => {
        if(err.response.status == 404){
            showErrorDialogFn(err.response.data.message)          
        } else {
            emitter.emit(Notification, {
                success: false,
                message: 'Something went wrong'
            })
        }
    }).finally(() => {
        fileUploadForm.processing = false
    })
}
const download = async (data) => {
    await api.post('download-files', {
         ids: data.ids
     }, { 
        headers: { 
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('token')
         },
        responseType: 'blob' // ضروري لاستقبال الملف كـ Blob
    }).
     then(res => {
        const blob = new Blob([res.data], { type: res.headers["content-type"] });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.style.display = "none";
        a.href = url;
        a.download = 'download';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        emitter.emit(Notification, {
            success: true,
            message: 'Files downloaded successfully'
        })
     }).catch(err => {
         emitter.emit(Notification, {
             success: false,
             message: 'Something went wrong'
         })
     })
}
const ShowShareFilesDialog = (ids) => {
    shareFilesDialog.value = true
    sharedIds.value = ids
}
const ShowAddToFavoriteFilesDialog = (ids) => {
    addToFavoriteDialog.value = true
    favoriteIds.value = ids
}

const openFileDialog = (data) => {
    showOpenFileDialog.value = true
    file.value = data
}
const deleteFromMyFavorite = async (ids) => {
    await api.post('delete-my-favorites',
        {
            ids: ids
        },
        { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}).then(res => {
            emitter.emit(ReloadFiles)
            emitter.emit(Notification, {
                success: true,
                message: 'Files romoved from favorites successfully'
            })
        }).catch(err => {
        })
}

onMounted(() => {
    emitter.on(FileUploadStarted, uploadFiles)
    emitter.on(ShareFiles, ShowShareFilesDialog)
    emitter.on(AddToMyFavorites, ShowAddToFavoriteFilesDialog)
    emitter.on(OpenFile, openFileDialog)
    emitter.on(DownloadFiles, download)
    emitter.on(RemoveFavorites, deleteFromMyFavorite)

    if(window.innerWidth < 700){
        isMobile.value = true
    } 

    window.onresize = () => {
        if(window.innerWidth < 765){
            isMobile.value = true
        } else{
            isMobile.value = false
        }
    }
})
</script>

