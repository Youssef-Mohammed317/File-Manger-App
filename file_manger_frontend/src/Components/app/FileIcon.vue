<template>
    <div class="w-6 h-6">
        <svg v-if="file.is_folder" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
        </svg>
        <template v-else>
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/image.png" alt="" v-if="isImage(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/pdf.png" alt="" v-else-if="isPdf(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/txt-file.png" alt="" v-else-if="isTxt(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/audio.png" alt="" v-else-if="isAudio(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/video.png" alt="" v-else-if="isVideo(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/excel.png" alt="" v-else-if="isExl(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/word.png" alt="" v-else-if="isWord(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/zip.png" alt="" v-else-if="isZip(file)">
            <img class="h-6 w-6 max-w-full" src="@/assets/Images/Icons/attach-file.png" alt="" v-else>
        </template>
    </div>
</template>

<script setup>
import { isAudio, isExl, isImage, isPdf, isTxt, isVideo, isWord, isZip } from '@/Helper/file-helper';


const { file } = defineProps({
    file: Object
}) 

</script>