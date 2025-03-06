
<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ans of ancestors.data" :key="ans.id" class="inline-flex items-center">
                    <Link v-if="!ans.parent_id" :href="route('myFiles')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                        </svg>

                        <span class="whitespace-nowrap ml-1">My Files</span>
                    </Link>
                    <div v-else class="flex items-center">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                        <Link :href="route('myFiles', {folder: ans.path})" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            {{ ans.name }}
                        </Link>
                    </div>
                </li>
            </ol>
            <PrimaryButton @click="downloadSelected()">
                <span class="me-2">Download</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </PrimaryButton>
            <PrimaryButton @click="deleteSelected()">
                <span class="me-2">Delete</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </PrimaryButton>
        </nav>
       <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 ps-6 pl-4 py-4 text-left w-[30px] m-x-[30px]">
                            <input
                                type="checkbox"
                                v-model="allSelected"
                                :checked="allSelected"
                                @change="onSelectAll" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            /> 
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Owner
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Last Modified
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Size
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file of files.data" :key="file.id" @dblclick="openFolder(file)" 
                    @click="selected[file.id] = !selected[file.id];onSelect()"
                    class="border-b transition duration-300 ease-in-out  cursor-pointer"
                    :class="selected[file.id] ? 'bg-blue-50 hover:bg-blue-100':'bg-white hover:bg-gray-100'"
                    >
                        <td class="ps-6 pl-4 ps-4 sm:ps-6 sm:pl-4 py-4 text-left w-[30px] m-x-[30px] whitespace-nowrap text-sm font-medium text-gray-900">
                            <input
                                @change="onSelect"
                                type="checkbox"
                                v-model="selected[file.id]"
                                :checked="selected[file.id]"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />    
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-4">
                                <FileIcon :file="file" />
                                {{file.name}} 
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{file.owner}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{file.updated_at}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ !file.is_folder ? calcFileSize(file.size):'' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="!files.data.length" class="py-8 text-center text-lg text-gray-400">
                There is no data in this folder
            </div>
       </div>
    </AuthenticatedLayout>
</template>

<script setup >
import FileIcon from '@/Components/app/FileIcon.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { DeleteFilesCompleted, emitter, ShowDeleteFileConfirmation } from '@/event-bus';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { all } from 'axios';
import { onMounted, onUpdated, reactive, ref } from 'vue';

const { files } = defineProps({
    files: Object,
    folder: Object,
    ancestors: Object
})

const allSelected = ref(false)

const selected = ref({})

const page = usePage()

// const emit = defineEmits(['close'])

const openFolder = (file) => {
    if(!file.is_folder) return
    router.visit(route('myFiles', {folder: file.path}))
}

const onSelectAll = () => {
    files.data.forEach(file => {
        selected.value[file.id] = allSelected.value
        if(!allSelected.value){
            delete selected.value[file.id]
        }
    });
}

const onSelect = () => {
    allSelected.value = true
    files.data.forEach(file => {
        if(!selected.value[file.id]){
            allSelected.value = false
            delete selected.value[file.id]
        } 
    })
}


const deleteSelected = () => {
    if(Object.keys(selected.value).length){
        emitter.emit(ShowDeleteFileConfirmation, Object.keys(selected.value))
    }
}
onMounted(() => {
 emitter.on(DeleteFilesCompleted, () => {
    selected.value = {}
    allSelected.value = false
 })
})

const downloadSelected = () => {
    if(Object.keys(selected.value).length){
        const form = useForm({
            parent_id: page.props.folder.data.id,
            ids: Object.keys(selected.value)
        })
        form.get(route('file.download'),{
            preserveScroll: true,
            onSuccess: () => {
                form.reset()
            },
            onError: () => {
                //  console.log('error')
            }
        })
    }
}

const calcFileSize = (size) => {
    if (size < 1024) return size + " B";
    else if (size < 1048576) return (size / 1024).toFixed(2) + " KB";
    else if(size < 1073741824) return (size / 1048576).toFixed(2) + " MB";
    else if(size < 1099511627776) return (size / 1073741824).toFixed(2) + " GB";
    else return (size / 1099511627776).toFixed(2) + " TB";
}
</script>
