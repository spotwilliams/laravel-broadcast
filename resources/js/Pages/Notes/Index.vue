<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My notes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="w-full whitespace-nowrap">
                            <tr class="text-left font-bold">
                                <th class="px-6 pt-6 pb-4">Title</th>
                                <th class="px-6 pt-6 pb-4">Action</th>
                            </tr>
                            <tr v-for="note in notes.data" :key="note.id"
                                class="hover:bg-gray-100 focus-within:bg-blue-100">
                                <td class="border-t">
                                    {{ note.title }}
                                </td>
                                <td class="border-t">
                                    <breeze-nav-link :href="route('edit_note', note.slug)">
                                        Edit
                                    </breeze-nav-link>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>

import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeNavLink from "@/Components/NavLink";

export default {
    metaInfo: {title: 'Notes'},
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,

    },
    props: {
        notes: Object
    },

}


</script>
