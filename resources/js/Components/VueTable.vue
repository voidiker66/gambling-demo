<script>
import { computed, nextTick } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

export default {
    data() {
        return {
            data: [],
            filter: {},
            sortBy: 'id',
            reverseSort: false,
            defaultFilters: {
                perPage: {
                    label: 'Results Per Page',
                    type: Array,
                    options: [
                        10, 20, 30, 50, 100
                    ],
                    default: 10
                }
            },
            links: null,
            loading: true
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
            this.loading = true
            axios.get(url, {
                params: {
                    'filter': self.filter,
                    'sortBy': self.sortBy,
                    'order': self.reverseSort ? '1' : '0'
                }
            }).then((response) => {
                self.data = response.data.data
                self.links = response.data.links
                nextTick(() => {
                    self.loading = false
                })
            })
        },
        applyFilter() {
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
            this.loadData(event.target.value)
        },
        perPageSelected(event) {
            this.filter.perPage = event.target.value
            this.loadData(this.fullUrl)
        },
        sort(event) {
            this.reverseSort = this.sortBy === event.target.value ? !this.reverseSort : false
            this.sortBy = event.target.value
            this.loadData(this.fullUrl)
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
        },
    },
    components: {
      Pagination, Select, PulseLoader, PrimaryButton
    },
    mounted() {
        this.loadData(this.fullUrl)
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
                    class="inline-flex text-sm font-medium text-gray-900 dark:text-gray-100 items-center px-1 pt-2 border-b-2 border-transparent focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out"
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
            <Select v-if="filterRow.type === Array && filterRow.label !== defaultFilters.perPage.label"
                :label="filterRow.label"
                :options="filterRow.options"
            >
            </Select>
            <Select v-if="filterRow.type === Array && filterRow.label === defaultFilters.perPage.label"
                :label="filterRow.label"
                :options="filterRow.options"
                @results-per-page-selected="perPageSelected"
            >
            </Select>
        </div>
        <PrimaryButton
            class="ml-4"
            :disabled="false"
            @click="applyFilter"
        >
            Apply Filter
        </PrimaryButton>
        <PrimaryButton
            class="ml-4"
            :disabled="false"
            @click="resetFilter"
        >
            Reset Filter
        </PrimaryButton>
    </div>
    <div v-show="!loading" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
        <table
            class="flex-auto justify-center items-center py-6 sm:pt-0 bg-gray-500 dark:bg-gray-500 border border-collapse w-full"
        >
            <thead>
                <tr class="bg-gray-900">
                    <th
                        :key="field"
                        v-for="(field, key) in fields"
                        class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left"
                    >
                        <button @click="sort" :value="key" class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-100 min-w-full min-h-full">{{ field.label }}</button>
                    </th>
                </tr>
            </thead>
            <template
                :key="row.id"
                v-for="row in data"
            >
                <tr class="hover:bg-gray-700">
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
        <Pagination :links="links" @pagination-clicked="paginationClicked" />
    </div>
    <div class="flex justify-center p-6">
        <PulseLoader :loading="loading"></PulseLoader>
    </div>
</template>
