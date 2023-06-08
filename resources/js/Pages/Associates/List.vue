<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VueTable from '@/Components/VueTable.vue'

const filters = {
    'range': {
        label: 'Range (km)',
        type: String
    },
    'latitude': {
        label: 'Latitude',
        type: String
    },
    'longitude': {
        label: 'Longitude',
        type: String
    },
}

const fields = {
    id: {
        label: 'ID',
        type: String
    },
    name: {
        label: 'Name',
        type: String
    },
    affiliate_id: {
        label: 'Affiliate ID',
        type: String,
    },
    latitude: {
        label: 'Latitude',
        type: String
    },
    'longitude': {
        label: 'Longitude',
        type: String
    },
    'created_at': {
        label: 'Date Imported',
        type: Date
    },
    'updated_at': {
        label: 'Date Updated',
        type: Date
    }
}

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Associates List" />
        <VueTable
            :url="'api/associates/list'"
            :filters="filters"
            :fields="fields"
        >

        </VueTable>
    </AuthenticatedLayout>
</template>
