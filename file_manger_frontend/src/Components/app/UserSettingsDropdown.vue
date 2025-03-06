<template>
<div class="top-16 w-32 text-right">
  <Menu as="div" class="relative inline-block text-left">
    <MenuButton
      ref="trigger"
      class="cursor-pointer inline-flex w-full  justify-center rounded-md text-gray-800 px-3 md:px-4 py-2 text-xs md:text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
    >
      {{ name }}
      <ChevronDownIcon
              class="mr-2 mt-[-3px] md:mt-[-1px] h-5 w-5 text-gray-800"
              aria-hidden="true"
            />
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
      class="absolute p-2 text-sm right-[10px] z-50 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
      >

      <MenuItem
      class="flex items-center justify-start px-3 py-2 hover:bg-gray-100 rounded"
      >
        <router-link :to="{ name:'profile' }" 
          active-class="bg-gray-200 hover:bg-gray-200">
            Profile
        </router-link>
      </MenuItem>
      <MenuItem
        class="flex items-center justify-start px-3 py-2 hover:bg-gray-100 rounded"
        >
        <a class="cursor-pointer" @click.prevent="logout">
          Log out
        </a>
      </MenuItem>
    </MenuItems>
</transition>
  </Menu>
</div>
</template>
  
<script setup>
import { emitter, Notification } from '@/event-bus';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const trigger = ref(null)
const router = useRouter()
const logout = () => {
  emitter.emit(Notification, {
    success:true,
    message: 'Logged out successfully'
  })
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push({
    name: 'login'
  })
}
const name = ref('')
onMounted(() => {
  if(localStorage.getItem('user')) { 
    name.value = JSON.parse(localStorage.getItem('user')).name.split(' ')[0]
  }

  emitter.on('nameChanged', () => {
    if(localStorage.getItem('user')) { 
      name.value = JSON.parse(localStorage.getItem('user')).name.split(' ')[0]
    }
  })
})

</script>
