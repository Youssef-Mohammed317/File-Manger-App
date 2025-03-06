<template>
    <Model :show="modelValue" @close="closeModal" >
         <div class="p-6 h-full w-full overflow-y-auto flex justify-center items-center">
            <div class="w-full h-full mx-auto bg-white rounded-lg shadow-md flex flex-col justify-between">
                <!-- File Preview -->
                <div v-if="isImage" class="relative">
                    <img
                    :src="file.url"
                    :alt="file.name"
                    class="w-full min-w-sm h-full object-cover"
                    />
                </div>
            
                <!-- PDF Viewer -->
                <div v-else-if="isPdf" class="relative">
                    <embed
                    :src="file.url"
                    type="application/pdf"
                    class="w-full h-full object-cover"
                    title="PDF Viewer"
                    />
                </div>
            
                <!-- Video Player -->
                <div v-else-if="isVideo" class="relative">
                    <video
                    controls
                    :src="file.url"
                    class="w-full h-full object-cover"
                    ></video>
                </div>
            
                <!-- Audio Player -->
                <div v-else-if="isAudio" class="flex items-center justify-center bg-gray-100 h-48">
                    <audio controls class="w-full">
                    <source :src="file.url" :type="file.mime" />
                    Your browser does not support the audio element.
                    </audio>
                </div>
            
                <!-- File Details -->
                <div class="p-4" >
                    <h3 class="text-lg font-semibold text-gray-800">{{ file.name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ fileSize }} • {{ fileType }}</p>
                    <p class="text-sm text-gray-500 mt-1">Uploaded {{ file.created_at }}</p>
            
                    <!-- Download Button -->
                    <a
                    :href="file.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="my-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out"
                    >
                    View / Download
                    </a>
                </div>
            </div>
        </div> 
    </Model>
</template>

<script setup>
import Model from '@/Components/Modal.vue'
import { computed } from "vue";

const { modelValue, file } = defineProps({
    modelValue: Boolean,
    file: Object,
})

const emit = defineEmits(['update:modelValue'])

const closeModal = () => {
    emit('update:modelValue')
}

  
// Computed Properties
const isImage = computed(() => file.mime.startsWith("image/"));
const isPdf = computed(() => file.mime === "application/pdf");
const isVideo = computed(() => file.mime.startsWith("video/"));
const isAudio = computed(() => file.mime.startsWith("audio/"));

// File Type and Size
const fileType = computed(() => file.mime.split("/")[0].toUpperCase());
const fileSize = computed(() => file.size);

</script>
