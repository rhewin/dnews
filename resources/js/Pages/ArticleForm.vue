<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    article: {
        type: Object,
        required: false,
    },
    csrfToken: String
});

const form = useForm({
    id: props.article?.data?.id || '',
    title: props.article?.data?.title || '',
    content: props.article?.data?.content || '',
    status: props.article?.data?.status || 'draft',
});

const create = () => {
    form.post(route('article.create'));
};

const update = () => {
    form.put(route('article.update', { id: form.id }));
};

const archive = () => {
    form.status = 'archived';
    form.delete(route('article.archive', { id: form.id }));
};

</script>

<template>
    <Head title="Form" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-4xl">
                <Header :csrfToken="csrfToken" :route="route" />
                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">
                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                            <Link :href="`/`">&larr; Back</Link>
                            <h2 class="m-auto">Form Article</h2>
                            <form @submit.prevent="update" class="w-full" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="relative mt-6">
                                    <label for="title" class="leading-7 text-sm text-gray-600">Title</label>
                                    <input v-model="form.title" type="text" id="title" name="title" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
                                </div>
                                <div class="relative mt-6">
                                    <label for="content" class="leading-7 text-sm text-gray-600">Content</label>
                                    <froala id="content" name="content" :tag="'textarea'" v-model:value="form.content"></froala>
                                    <div v-if="form.errors.content" class="text-red-600 text-sm">{{ form.errors.content }}</div>
                                </div>
                                <div class="relative mt-6">
                                    <div class="current-status" v-if="article?.data">
                                        <label class="leading-7 text-sm text-gray-600">Status: <strong class="text-white capitalize">{{ article.data.status }}</strong></label>
                                        <label for="content" class="leading-7 text-sm text-gray-600 mr-3">, change into: </label>
                                    </div>
                                    <select v-model="form.status" name="status" id="status" class="w-1/4 bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-8 transition-colors duration-200 ease-in-out" :disabled="!form.id">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div class="mt-6">
                                    <PrimaryButton class="mr-6" :disabled="form.processing" @click="create" v-if="!form.id">Create</PrimaryButton>
                                    <PrimaryButton class="mr-6" :disabled="form.processing" @click="update" v-if="form.id">Update</PrimaryButton>
                                </div>
                                <div class="archived-action-wrapper" v-if="form.id && article.data.status != 'archived'">
                                    <div class="mt-6"> - OR - </div>
                                    <div class="mt-6">
                                        <DangerButton class="mr-6" :disabled="form.processing" @click="archive">Archived</DangerButton>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Rheza Sindhuwinata - rheza.s@gmail.com
                </footer>
            </div>
        </div>
    </div>
</template>
