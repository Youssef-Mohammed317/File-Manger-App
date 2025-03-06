<template>
  <div class="fixed flex z-50 flex-col bottom-[20px] right-[20px] w-[250px] p-2 rounded bg-gray-950">
     <span class="text-white">Uploading {{FilesNumbers}} files</span>
    <span class="loader"></span>
  </div>
</template>
<script setup>
const {FilesNumbers} = defineProps({
    FilesNumbers: Number
});
</script>
<style scoped>
.loader {
  width: 100%;
  height: 4.8px;
  display: inline-block;
  position: relative;
  background: rgba(255, 255, 255, 0.15);
  overflow: hidden;
}
.loader::after {
  content: '';
  width: 192px;
  height: 4.8px;
  background: #FFF;
  position: absolute;
  top: 0;
  left: 0;
  box-sizing: border-box;
  animation: animloader 2s linear infinite;
}

@keyframes animloader {
  0% {
    left: 0;
    transform: translateX(-100%);
  }
  100% {
    left: 100%;
    transform: translateX(0%);
  }
}
    
</style>