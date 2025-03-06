<template>
    <form class="w-[600px] h-[80px] flex items-center">
        <TextInput type="text"
                    class="block w-full mr-2 h-[38px] px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 sm:text-sm"
                    autocomplete
                    v-model="value"
                    @focus="focus"
                    placeholder="Search for files and folders" 
                    ref="searchInputField"
                />
    </form>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import { emitter, FocusSearchInput, SearchInput } from '@/event-bus';

import { computed } from 'vue';

const value = computed({
  get: (oldValue) => {
      return oldValue
  },
  set: (newValue) => {
    emitter.emit(SearchInput, newValue)
  }
});

const focus = () => {
    emitter.emit(FocusSearchInput)
    emitter.emit(SearchInput, value.value ? value.value : '')
}

</script>