<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create note
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <breeze-validation-errors class="mb-4"/>


                        <form @submit.prevent="submit">
                            <div>
                                <breeze-label for="title" value="Title"/>
                                <breeze-input id="title" type="text" class="mt-1 block w-full" v-model="form.title"
                                              required autofocus autocomplete="title"/>
                            </div>

                            <div class="mt-4">
                                <breeze-label for="body" value="Body"/>
                                <breeze-text-area id="body" type="body" class="mt-1 block w-full" v-model="form.body"
                                                  required
                                                  autocomplete="body"/>
                            </div>


                            <div class="flex items-center justify-end mt-4">
                                <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }"
                                               :disabled="form.processing"
                                >
                                    Save changes
                                </breeze-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeValidationErrors from '@/Components/ValidationErrors'
import BreezeButton from "@/Components/Button";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeTextArea from "@/Components/TextArea"

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeValidationErrors,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeTextArea,
    },
    data() {
        return {
            form: this.$inertia.form({
                title: '',
                body: '',
                processing: false,
            })
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('store_note'))
        }
    },
}
</script>
