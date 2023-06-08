<script>
  import { Link } from '@inertiajs/vue3';
  import SecondaryButton from '@/Components/SecondaryButton.vue'
  
  export default {
    props: {
      links: Array,
    },
    components : {
      Link, SecondaryButton
    },
    methods: {
      clicked(event) {
        this.$emit('pagination-clicked', event)
      }
    }
  }
</script>

<template>
    <div v-if="links !== null && links.length > 3">
        <div class="flex flex-wrap -mb-1">
        <template v-for="(link, key) in links">
            <div v-if="link.url === null" :key="link.label" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
            <SecondaryButton v-else :value="link.url" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white': link.active }" @click="clicked" v-html="link.label" />
        </template>
        </div>
    </div>
</template>  