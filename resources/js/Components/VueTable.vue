<script>
import { computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue';

export default {
    data() {
        return {
            data: [],
            filter: {},
            defaultFilters: {
                perPage: {
                    label: 'Results Per Page',
                    type: Array,
                    options: [
                        10, 20, 30
                    ],
                    default: 10
                }
            },
            links: null
        }
    },
    props: {
        url: {
            type: String,
            required: true
        },
        filters: {
            type: Object,
            required: true
        },
        fields: {
            type: Object,
            required: true
        }
    },
    methods: {
        loadData(url) {
            let self = this
            axios.get(url, {
                params: {
                    'filter': self.filter,
                }
            }).then((response) => {
                self.data = response.data.data
                self.links = response.data.links
            })
        },
        applyFilter() {
            this.filter = {
                ...this.filter,
                perPage: 30
            };
            this.loadData(this.fullUrl)
        },
        resetFilter() {
            this.filter = {}
            this.loadData(this.fullUrl)
        },
        dateField(date) {
            return (new Date(date)).toDateString()
        },
        paginationClicked(event) {
            console.log(event);
        }
    },
    computed: {
        allFilters() {
            return {
                ...this.filters,
                ...this.defaultFilters
            }
        },
        fullUrl() {
            return "http://127.0.0.1:8000/"+this.url
        }
    },
    components: {
      Pagination
    },
    mounted() {
        this.loadData(this.fullUrl)
    },
    unmounted() {
        // Removes listener for pagination-clicked (inertia specific)
        router.on('pagination-clicked', (event) => {})
    }
}
</script>

<template>
    <div 
        class="flex flex-row flex-initial sm:justify-start items-center pt-6 sm:pt-0 bg-gray-500 dark:bg-gray-500"
    >
        <div v-for="(filterRow, key) in allFilters" class="flex flex col p-2">
            <InputLabel :for="key" v-if="filterRow.type !== Array">
                <span
                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out"
                >
                    {{ filterRow.label }}
                </span>
            </InputLabel>
            <input
                v-if="filterRow.type === String"
                :id="key"
                type="text"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block leading-5"
                v-model="filter[key]"
            />
            <Select v-if="filterRow.type === Array"
                :label="filterRow.label"
                :options="filterRow.options"
            >
            </Select>
        </div>
        <PrimaryButton
            class="ml-4"
            :class="'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block leading-5'"
            :disabled="false"
            @click="applyFilter"
        >
            Apply Filter
        </PrimaryButton>
        <PrimaryButton
            class="ml-4"
            :class="'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block leading-5'"
            :disabled="false"
            @click="resetFilter"
        >
            Reset Filter
        </PrimaryButton>
    </div>
    <table
        class="flex-auto justify-center items-center py-6 sm:pt-0 bg-gray-500 dark:bg-gray-500 border border-collapse w-5/6"
        @pagination-clicked="paginationClicked"
    >
        <thead>
            <tr>
                <th
                    :key="field"
                    v-for="field in fields"
                    class="w-1/2 border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left"
                >
                    <span class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-100">{{ field.label }}</span>
                </th>
            </tr>
        </thead>
        <template
            :key="row.id"
            v-for="row in data"
        >
            <tr>
                <td
                    :key="field.label"
                    v-for="(field, key) in fields"
                    class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400"
                >
                    <span
                        v-if="field.type === String"
                        class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-100"
                    >
                        {{ row[key] }}
                    </span>
                    <span
                        v-if="field.type === Date"
                        class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-100"
                    >
                        {{ dateField(row[key]) }}
                    </span>
                </td>
            </tr>
        </template>
    </table>
    <Pagination :links="links" />
</template>
