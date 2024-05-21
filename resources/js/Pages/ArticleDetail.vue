<script setup>
import {ref} from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
    csrfToken: String
});

const form = useForm({
    article_id: props.article?.data?.id || '',
    content: '',
});

const btnComment = ref(false);
const isLiked = ref(props.article?.meta?.is_like);

const comment = () => {
    if (!btnComment.value) {
        form.post(route('comment.create'));
        btnComment.value = true;
    }
};

const toggleLike = (user) => {
    if (user) {
        isLiked.value = !isLiked.value
        if (isLiked.value) {
            form.post(route('article.like', { id: form.article_id }));
        } else {
            form.post(route('article.dislike', { id: form.article_id }));
        }
    }
};

</script>

<template>
    <Head :title="article.data.title" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-4xl">
                <Header :csrfToken="csrfToken" :route="route" />
                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">
                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                            <Link :href="`/`">&larr; Back</Link>
                            <div class="mt-6 m-auto text-center">
                                <h2><strong>{{ article.data.title }}</strong></h2>
                                <h6 class="mt-3">By {{ article.data.user.name }} &#183; <span class="capitalize italic">{{ article.data.status }}</span></h6>
                            </div>
                            <div class="mb-6 text-lg w-full border-b border-gray-800 pb-4 text-right">
                                <form @submit.prevent="like" class="w-full" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="POST">
                                    <button type="button" class="bg-opacity-0 outline-none border-none" :disabled="form.processing" @click="toggleLike($page.props.auth.user)">
                                        <svg v-if="isLiked" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline align-sub" viewBox="0 0 24 24"><title>heart</title><g fill="#F7F7F7"><g data-name="Layer 2"><path d="M12 21a1 1 0 0 1-.71-.29l-7.77-7.78a5.26 5.26 0 0 1 0-7.4 5.24 5.24 0 0 1 7.4 0L12 6.61l1.08-1.08a5.24 5.24 0 0 1 7.4 0 5.26 5.26 0 0 1 0 7.4l-7.77 7.78A1 1 0 0 1 12 21z" data-name="heart"></path></g></g></svg>

                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline align-sub" viewBox="0 0 24 24"><title>heart-outline</title><g fill="#F7F7F7"><g data-name="Layer 2"><path d="M12 21a1 1 0 0 1-.71-.29l-7.77-7.78a5.26 5.26 0 0 1 0-7.4 5.24 5.24 0 0 1 7.4 0L12 6.61l1.08-1.08a5.24 5.24 0 0 1 7.4 0 5.26 5.26 0 0 1 0 7.4l-7.77 7.78A1 1 0 0 1 12 21zM7.22 6a3.2 3.2 0 0 0-2.28.94 3.24 3.24 0 0 0 0 4.57L12 18.58l7.06-7.07a3.24 3.24 0 0 0 0-4.57 3.32 3.32 0 0 0-4.56 0l-1.79 1.8a1 1 0 0 1-1.42 0L9.5 6.94A3.2 3.2 0 0 0 7.22 6z" data-name="heart"></path></g></g></svg> {{ article.meta.like_count }}
                                    </button>
                                </form>
                            </div>
                            <div class="mt-6 mb-6 font-serif text-lg w-full article-content" v-html="article.data.content"></div>
                            <div class="mt-12 w-full rounded-lg border-2 border-gray-700 border-dotted p-6" v-if="$page.props.auth.user">
                                <form @submit.prevent="comment" class="w-full" method="post" v-if="!btnComment">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="POST">
                                    <textarea v-model="form.content" id="content" name="content" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" placeholder="What's your opinion?"></textarea>
                                    <div v-if="form.errors.content" class="text-red-600 text-sm">{{ form.errors.content }}</div>
                                    <PrimaryButton class="mt-6 mr-6" :disabled="form.processing" @click="comment">Submit Comment</PrimaryButton>
                                </form>
                                <p v-else>Your comment submitted! Thank you.</p>
                            </div>
                            <!-- Display comments -->
                            <div class="mt-6 mb-6 w-full">
                                <h5><strong>Comments ({{ article.data.comments.length }})</strong></h5>
                                <ul>
                                    <li v-for="comment in article.data.comments" :key="comment.id" class="mt-3 pt-4 pb-8 border-t border-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mr-3 inline float-left align-sub" viewBox="0 0 256 256"><title>user-circle-duotone</title><g fill="#F7F7F7"><path fill="none" d="M0 0H256V256H0z"></path><path d="M128,32A96,96,0,0,0,63.8,199.38h0A72,72,0,0,1,128,160a40,40,0,1,1,40-40,40,40,0,0,1-40,40,72,72,0,0,1,64.2,39.37A96,96,0,0,0,128,32Z" opacity=".2"></path><path d="M63.8,199.37a72,72,0,0,1,128.4,0" fill="none" stroke="#F7F7F7" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><circle cx="128" cy="128" r="96" fill="none" stroke="#F7F7F7" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><circle cx="128" cy="120" r="40" fill="none" stroke="#F7F7F7" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle></g></svg>

                                        <strong>{{ comment.user.name }}</strong><br>
                                        {{ comment.content }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </main>

                <Footer />
            </div>
        </div>
    </div>
</template>
