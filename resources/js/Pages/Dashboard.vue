<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
                {{ $page.props.user.name }}
                {{ username }}
            </h2>
        </template>
        <div>

        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto my-4 sm:px-6 lg:px-8" >
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <Profile />
                </div>
            </div>
             <div class="flex md:flex-row-reverse flex-wrap">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                        <PostList />
                </div>
            </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import {
        defineComponent
    } from 'vue'
    import Profile from '@/Pages/Instagram/Profile.vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import PostList from '@/Pages/Instagram/PostList.vue'
    export default defineComponent({
        props: ['username'],
        components: {
            AppLayout,
            Welcome,
            PostList,
            Profile
        },
    })
</script>
