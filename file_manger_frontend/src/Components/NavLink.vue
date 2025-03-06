<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-indigo-700 bg-indigo-100 focus:outline-none transition duration-150 ease-in-out'
        : 'flex items-center px-3 py-2 mb-1 rounded-lg text-sm font-medium leading-5 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
