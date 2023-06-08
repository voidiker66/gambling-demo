<script>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

export default {
    data() {
      return {
          selected: null
      }
  },
  props: {
    label: {
        type: String,
        required: true
    },
    options: {
        type: Array,
        required: true
    }
  },
  methods: {
    select(event) {
      this.selected = event.target.value
      this.$emit(this.eventName, event)
    }
  },
  computed: {
    eventName() {
      return this.label
      .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
      .map(x => x.toLowerCase())
      .join('-')
      + '-selected'
    }
  },
  mounted() {
    this.selected = this.options.length ? this.options[0] : null
  },
  components: {
    Menu, MenuButton, MenuItem, MenuItems, ChevronDownIcon
  }
}
</script>

<template>
    <Menu as="div" class="relative inline-block text-left">
      <div>
        <MenuButton class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
          {{ label }}
          <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
        </MenuButton>
      </div>
  
      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItems class="absolute right-0 z-10 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
          <div class="py-1">
            <MenuItem
                :key="option"
                v-slot="{ active }"
                v-for="option in options"
                class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-400 text-right"
                @click="select"
            >
              <button :value="option" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{ option }}</button>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
</template>