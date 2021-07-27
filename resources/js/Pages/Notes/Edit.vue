<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editing note: {{ form.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <breeze-validation-errors class="mb-4"/>

                        <div v-if="wasSuccessful"
                             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                             role="alert">
                            <strong class="font-bold">Goes ok! </strong>
                            <span class="block sm:inline" v-text="form.status"></span>
                            <span class="absolut top-0 bottom-0 right-0 px-4 py-3"><svg
                                class="fill-current h-6 w-6 text-green-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg></span>
                        </div>

                        <form @submit.prevent="submit">
                            <div>
                                <breeze-label for="title" value="Title"/>
                                <breeze-input id="title" type="text" class="mt-1 block w-full" v-model="form.title"
                                              @keydown="editingNote"
                                              required autofocus autocomplete="title"/>
                            </div>

                            <div class="mt-4">
                                <breeze-label for="body" value="Body"/>
                                <breeze-text-area id="body" type="body" class="mt-1 block w-full" v-model="form.body"
                                                  @keydown="editingNote"
                                                  required
                                                  autocomplete="body"/>
                            </div>


                            <div class="flex items-center justify-end mt-4">
                                <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }"
                                               :disabled="form.processing"
                                               @click="updateNote">
                                    Save changes
                                </breeze-button>
                            </div>
                        </form>

                        <p>
                            Users editing this note: <span
                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-indigo-600 rounded-full">{{
                                form.usersEditing.length
                            }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeNavLink from "@/Components/NavLink";
import BreezeValidationErrors from '@/Components/ValidationErrors'
import BreezeButton from "@/Components/Button";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeTextArea from "@/Components/TextArea"

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,
        BreezeValidationErrors,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeTextArea,
    },
    props: {
        note: Object
    },
    data() {
        return {
            form: {
                title: this.note.title,
                body: this.note.body,
                usersEditing: [],
                status: '',
                processing: false,
            }
        }
    },
    methods: {
        editingNote() {
            let channel = Echo.join(`private-note.${this.note.slug}`)

            // let the channel know that we have some news to show
            setTimeout(() => {
                channel.whisper('editing', {
                    title: this.form.title,
                    body: this.form.body
                })
            }, 1000);
        },
        updateNote() {
            // lock button
            this.form.processing = true;
            // payload
            let note = {
                title: this.form.title,
                body: this.form.body,
            };

            // go
            axios.put(`/notes/${this.note.slug}/`, note)
                .then(response => {

                    this.form.status = response.data.success;

                    this.form.title = response.data.note.title;
                    this.form.body = response.data.note.body;
                    // clear is status after 1s
                    setTimeout(() => {
                        this.form.status = '';
                    }, 2000);

                    this.form.processing = false;

                    Echo.join(`private-note.${this.note.slug}`)
                        .whisper('saved', {
                            status: response.data.success,
                        });
                })
                .catch(error => {
                    console.log(error)
                    this.form.processing = false;
                });

        },
    },
    computed: {
        wasSuccessful() {
            return this.form.status !== '';
        }
    },
    mounted() {
        Echo.join(`private-note.${this.note.slug}`)
            .here(users => {
                this.form.usersEditing = users;
            })
            .joining(user => {
                this.form.usersEditing.push(user);
            })
            .listenForWhisper('editing', e => {
                this.form.title = e.title;
                this.form.body = e.body;
            })
            .listenForWhisper('saved', e => {
                this.form.status = e.status;
                setTimeout(() => {
                    this.form.status = '';
                }, 2000);
            })
    },
}
</script>
