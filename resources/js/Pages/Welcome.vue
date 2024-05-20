<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps({
    articles: {
        type: Object,
        required: true,
    },
    csrfToken: String
});

const formatDate = (dateString, article_status) => {
    if (dateString && article_status == 'published') {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const [datePart, _] = dateString.split(' ');
        const [year, month, day] = datePart.split('-');
        const formattedDate = `${monthNames[month-1]} ${day}, ${year}`;
        return formattedDate;
    }
    return `in ${article_status}`;
};

const form = useForm({
    keyword: null
});

const handleSearch = () => {
    if (form.keyword.length > 2) {
        form.get(route('article.index', {keyword: form.keyword}), {preserveState: true});
    }
    if (form.keyword.length <= 0) {
        form.keyword = '';
        form.get(route('article.index', {}));
    }
};
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-4xl">
                <Header :csrfToken="csrfToken" :route="route" />
                <main class="mt-6">
                    <div v-if="$page.props.auth.user" class="text-white mb-6">
                        Welcome, {{ $page.props.auth.user.name }}
                    </div>
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">
                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">

                            <!-- Search Bar -->
                            <form @submit.prevent="handleSearch" class="flex rounded-full bg-[#0d1829] px-2 w-full max-w-[600px] border border-gray-600 m-auto mt-3 mb-6">
                                <input type="text" v-model="form.keyword" @input="handleSearch"
                                    class="w-full bg-[#0d1829] flex bg-transparent pl-4 pr-4 text-[#cccccc] outline-0 shadow-none border-0 rounded-full focus:outline-0 focus:shadow-none focus:border-0"
                                    placeholder="Search title or content...(min. 3 chars)"
                                />
                                <button type="button" class="relative p-3 bg-[#0d1829] rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><title>search-refraction</title><g fill="none"><path d="M21 21L16.65 16.65M11 6C13.7614 6 16 8.23858 16 11M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                </button>
                            </form>

                            <Link :href="`/article/form/`" v-if="$page.props.auth.user">
                                <PrimaryButton class="mr-6">Create New Article</PrimaryButton>
                            </Link>

                            <ul>
                                <li v-for="article in articles.data.list" :key="article.id" class="mb-9">
                                    <div class="text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 inline align-sub" viewBox="0 0 20 20"><title>user-solid-circle</title><g fill="#F7F7F7"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zM7 6v2a3 3 0 1 0 6 0V6a3 3 0 1 0-6 0zm-3.65 8.44a8 8 0 0 0 13.3 0 15.94 15.94 0 0 0-13.3 0z"></path></g></svg>

                                        {{ article.user.name }} &#183;
                                        {{ formatDate(article.published_date, article.status) }}

                                        <Link :href="`/article/form/${article.id}`" v-if="$page.props.auth.user && $page.props.auth.user.id == article.user_id" class="inline-block ml-3 text-yellow-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline align-sub">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                            Edit
                                        </Link>
                                    </div>
                                    <Link :href="`/article/${article.id}`" class="inline-block mr-3 ">
                                        <div class="text-2xl font-bold mt-2">
                                            {{ article.title }}
                                        </div>
                                        <div class="font-sans mt-2 text-justify" v-html="article.summary"></div>
                                    </Link>

                                </li>
                            </ul>

                            <!-- Display pagination links -->
                            <div >
                                <Link :href="route('article.index', { page: articles.data.current_page - 1 })" v-if="articles.data.current_page > 1" class="mr-6">
                                    &larr; Prev
                                </Link>
                                <Link :href="route('article.index', { page: articles.data.current_page + 1 })" v-if="articles.data.current_page < articles.data.last_page">
                                    Next &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    rheza.s@gmail.com
                </footer>
            </div>
        </div>
    </div>
</template>
