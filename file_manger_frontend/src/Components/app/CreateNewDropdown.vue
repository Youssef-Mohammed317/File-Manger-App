<template>
    <Menu as="div" class="relative block text-left">
    
    <MenuButton
    class="cursor-pointer flex w-full justify-start gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
    >
        <div class="h-5 w-5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </div>
        <span v-if="!isMobile" class="whitespace-nowrap overflow-hidden"> Create New</span>
    </MenuButton>

    <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
    >
        <MenuItems
        class="absolute left-[10px] z-50 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
        >
        <div class="px-1 py-1">
            <MenuItem>
            <a href="#" @click.prevent="showCreateFolderModal" class="text-sm text-gray-700 block px-4 py-2 rounded-md hover:text-gray-900 hover:bg-gray-100 ">
                New Folder
            </a>
            </MenuItem>
        </div>
        <div class="px-1 py-1">
            <FileUploadMenuItem />
            <FolderUploadMenuItem />
        </div>

        </MenuItems>
    </transition>
    </Menu>

    <CreateFolderModal v-model="createFolderModal"/>
</template>
  
<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import CreateFolderModal from '@/Components/app/CreateFolderModal.vue';
import { ref } from 'vue';
import FileUploadMenuItem from './FileUploadMenuItem.vue';
import FolderUploadMenuItem from './FolderUploadMenuItem.vue';

const { isMobile } = defineProps({
    isMobile: Boolean
})
const createFolderModal = ref(false)
const showCreateFolderModal = () => {
    createFolderModal.value = true
}
</script>
