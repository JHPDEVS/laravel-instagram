<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                IDDashboard
                {{ user.name }}
            </h2>
        </template>
        <div>

        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto my-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-if="user.id">
                               <div class=" px-48">
                        <div class="flex md:flex-row-reverse flex-wrap">
                            <div class="w-full md:w-3/4 p-4 text-center">
                                <div class="text-left pl-4 pt-3">
                                    <span class="text-base text-gray-700 text-2xl mr-2" v-if="user.id">{{ user.id }}</span>
                                    <span class="text-base text-gray-700 text-2xl mr-2" v-else>존재하지 않는 사용자</span>
                                    <span class="text-base font-semibold text-gray-700 mr-2">
                                        <button
                                            class="bg-transparent hover:bg-blue-500 text-gray-700 font-semibold hover:text-white py-2 px-4 border border-gray-600 hover:border-transparent rounded">Edit
                                            Profile</button>
                                    </span>
                                    <span class="text-base font-semibold text-gray-700">
                                        <button
                                            class="p-1 border-transparent text-gray-700 rounded-full hover:text-blue-600 focus:outline-none focus:text-gray-600"
                                            aria-label="Notifications">
                                            <svg class="h-8 w-8" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="1.5" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>

                                <div class="text-left pl-4 pt-3">
                                    <span class="text-base font-semibold text-gray-700 mr-2">
                                        <b>{{ postCount }}</b> posts
                                    </span>
                                </div>

                                <div class="text-left pl-4 pt-3">
                                    <span
                                        class="text-lg font-bold text-gray-700 mr-2">{{ user.name }}</span>
                                </div>

                                <div class="text-left pl-4 pt-3">
                                    <p class="text-base font-medium text-blue-700 mr-2">#graphicsdesigner #traveller
                                        #reader #blogger #digitalmarketer</p>
                                    <p class="text-base font-medium text-gray-700 mr-2">
                                        https://www.behance.net/hiravesona7855</p>
                                </div>
                            </div>

                            <div class="w-full md:w-1/4 p-4 text-center">
                                <div class="w-full relative md:w-3/4 text-center mt-8">
                                    <button class="flex rounded-full" id="user-menu" aria-label="User menu"
                                        aria-haspopup="true">
                                        <img class="rounded-full" :src="user.profile_photo_url" alt />
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>

                    </div>
                 <div v-else>
                      <div class="bg-gray-100 h-screen justify-center">
            <center class="mt-24 m-auto">
                <svg class="emoji-404 " enable-background="new 0 0 226 249.135" height="249.135" id="Layer_1"
                    overflow="visible" version="1.1" viewBox="0 0 226 249.135" width="226" xml:space="preserve">
                    <circle cx="113" cy="113" fill="#FFE585" r="109" />
                    <line enable-background="new    " fill="none" opacity="0.29" stroke="#6E6E96" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="8" x1="88.866" x2="136.866" y1="245.135" y2="245.135" />
                    <line enable-background="new    " fill="none" opacity="0.17" stroke="#6E6E96" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="8" x1="154.732" x2="168.732" y1="245.135" y2="245.135" />
                    <line enable-background="new    " fill="none" opacity="0.17" stroke="#6E6E96" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="8" x1="69.732" x2="58.732" y1="245.135" y2="245.135" />
                    <circle cx="68.732" cy="93" fill="#6E6E96" r="9" />
                    <path
                        d="M115.568,5.947c-1.026,0-2.049,0.017-3.069,0.045  c54.425,1.551,98.069,46.155,98.069,100.955c0,55.781-45.219,101-101,101c-55.781,0-101-45.219-101-101  c0-8.786,1.124-17.309,3.232-25.436c-3.393,10.536-5.232,21.771-5.232,33.436c0,60.199,48.801,109,109,109s109-48.801,109-109  S175.768,5.947,115.568,5.947z"
                        enable-background="new    " fill="#FF9900" opacity="0.24" />
                    <circle cx="156.398" cy="93" fill="#6E6E96" r="9" />
                    <ellipse cx="67.732" cy="140.894" enable-background="new    " fill="#FF0000" opacity="0.18"
                        rx="17.372" ry="8.106" />
                    <ellipse cx="154.88" cy="140.894" enable-background="new    " fill="#FF0000" opacity="0.18"
                        rx="17.371" ry="8.106" />
                    <path
                        d="M13,118.5C13,61.338,59.338,15,116.5,15c55.922,0,101.477,44.353,103.427,99.797  c0.044-1.261,0.073-2.525,0.073-3.797C220,50.802,171.199,2,111,2S2,50.802,2,111c0,50.111,33.818,92.318,79.876,105.06  C41.743,201.814,13,163.518,13,118.5z"
                        fill="#FFEFB5" />
                    <circle cx="113" cy="113" fill="none" r="109" stroke="#6E6E96" stroke-width="8" /></svg>
                <div class=" tracking-widest mt-4">
                    <span class="text-gray-500 text-6xl block"><span>죄송합니다. 페이지를 사용할 수 없습니다</span></span>
                    <span class="text-gray-500 text-xl">클릭하신 링크가 잘못되엇거나 페이지가 삭제되었습니다. Instagram으로 돌아가기.</span>
                </div>
            </center>
        </div>
                 </div>
                </div>
                
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <PostList :user_id="id"/>
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
    props: ['id'],
        components: {
            AppLayout,
            Welcome,
            PostList,
            Profile
        },
         data() {
            return {
               user : [],
               postCount : '',
            }
        },
        mounted() {
            axios.get('/users/' + this.id)
            .then(response=>{
                this.user = response.data.user
                this.postCount = response.data.postCount
            })
        }
    })
</script>
