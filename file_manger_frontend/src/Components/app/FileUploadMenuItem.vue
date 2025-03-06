<template>
    <MenuItem>
        <a href="#" onclick="document.getElementById('input-upload').click()" class="relative text-sm text-gray-700 block px-4 py-2 rounded-md hover:text-gray-900 hover:bg-gray-100 ">
            Upload Files
            <input @change="onChange" id="input-upload" multiple type="file" class="z-[-1] hidden">
        </a>
    </MenuItem>
</template>

<script setup>
import { emitter, FileUploadStarted } from '@/event-bus';
import { MenuItem } from '@headlessui/vue';

const onChange = (e) => {
    emitter.emit(FileUploadStarted, e.target.files)
}
</script>