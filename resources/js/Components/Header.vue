<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    route: Function,
    csrfToken: String
});
</script>


<template>
    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex lg:justify-center lg:col-start-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-auto text-white lg:h-12 lg:text-[#FF2D20]"  viewBox="0 0 100 100"><title>29</title><g fill="#F7F7F7"><g id="1715340893743-3297795_29.-Diamond" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><path id="1715340893743-3297795_Layer-1" stroke="#F7F7F7" stroke-width="4" d="M11.4331321 5L87.7530578 5 97.1577608 33.5148515 2.10825583 33.5148515z"></path><path id="1715340893743-3297795_Layer-2" stroke="#F7F7F7" stroke-width="4" d="M2.10825583 33.5148515L97.1577608 33.5148515 49.6330083 95.2970297z"></path><path id="1715340893743-3297795_Layer-3" stroke="#F7F7F7" stroke-width="4" d="M2.10825583 33.5148515L30.796684 33.5148515 49.6330083 95.2970297z"></path><path id="1715340893743-3297795_Layer-4" stroke="#F7F7F7" stroke-width="4" d="M97.1577608 33.5148515L68.4693326 33.5148515 49.6330083 95.2970297z"></path><path id="1715340893743-3297795_Layer-5" stroke="#F7F7F7" stroke-width="4" d="M11.6132063 5L30.553491 33.5148515 49.51234 5 68.7162385 33.5148515 87.4486024 5z"></path></g></g></svg>
        </div>
        <div class="flex lg:justify-center lg:col-start-2 text-white align-sub italic">Fresh News Brightening Your Day!</div>

        <nav class="-mx-3 flex flex-1 justify-end">
            <template v-if="$page.props.auth.user">
                <Link
                    :href="route('dashboard')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </Link>
                <form method="POST" :action="route('logout')">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">Logout</button>
                </form>
            </template>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Log in
                </Link>
                <Link
                    :href="route('register')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Register
                </Link>
            </template>
        </nav>
    </header>
</template>
