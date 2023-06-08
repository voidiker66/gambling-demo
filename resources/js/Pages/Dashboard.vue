<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    errors: {
        type: Object,
    }
});

const form = useForm({
    associateInputFile: null
});

const importAssociates = () => {
    form.post(route('import.associates'), {
        onFinish: () => {
            form.reset('associateInputFile')
        },
        onError: errors => {console.log(errors)},
    })
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form  @submit.prevent="importAssociates">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2">
                        <label class="block text-sm font-medium p-6 text-gray-900 dark:text-gray-100" for="file_input">Upload file</label>
                        <input 
                            class="ml-4 block w-80 text-sm text-gray-800 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-200 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input"
                            type="file"
                            @input="form.associateInputFile = $event.target.files[0]"
                        >
                        <InputError class="mt-2" :message="form.errors.associateInputFile" />
                        <PrimaryButton
                            class="ml-4 my-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.associateInputFile === null || form.processing"
                            @click="importAssociates"
                        >
                            Submit
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
