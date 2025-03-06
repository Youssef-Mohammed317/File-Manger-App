<template>
  <GuestLayout v-if="$route.meta.layout == 'guest'">
    <RouterView />
  </GuestLayout>
  <AuthenticatedLayout v-else>
    <RouterView :key="reload + $route.fullPath" />
  </AuthenticatedLayout>
  <Notifications />
</template>

<script setup>
import { RouterView, useRoute, useRouter } from 'vue-router'
import GuestLayout from './Layouts/GuestLayout.vue';
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { emitter, FocusSearchInput, ReloadFiles } from './event-bus';
import Notifications from './Components/app/Notifications.vue';

const reload = ref(0)

const router = useRouter()


onMounted(() => {
  emitter.on(ReloadFiles, () => {
    reload.value++
  })
  emitter.on(FocusSearchInput, () => {
    router.push({ name: 'search' })
  })
})


</script>
