<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <nav 
        class="fixed top-0 right-0 p-6 flex gap-4 text-md text-gray-700 justify-end items-center z-50 font-semibold"
        >
            <router-link 
            :to="{ name: 'login' }"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none"
            active-class="text-gray-900"
            >
            Login</router-link>
            <router-link 
            :to="{ name: 'register' }"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none"
             active-class="text-gray-900"
            >
            Register
        </router-link>
        </nav>
        <div>
            <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
        </div>
        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
