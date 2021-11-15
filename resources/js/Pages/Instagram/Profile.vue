<template>

    <div class=" px-48">
      <div class="flex md:flex-row-reverse flex-wrap">
        <div class="w-full md:w-3/4 p-4 text-center">
          <div class="text-left pl-4 pt-3">
            <span class="text-base text-gray-700 text-2xl mr-2">{{ user.name }}</span>
            <span class="text-base font-semibold text-gray-700 mr-2">
              <button
                class="bg-transparent hover:bg-blue-500 text-gray-700 font-semibold hover:text-white py-2 px-4 border border-gray-600 hover:border-transparent rounded"
              >프로필 편집</button>
            </span>
          </div>

          <div class="text-left pl-4 pt-3">
            <span class="text-base font-semibold text-gray-700 mr-2">
              <b>{{ postCount }}</b> posts
            </span>
          </div>

          <div class="text-left pl-4 pt-3">
            <span class="text-lg font-bold text-gray-700 mr-2">{{ user.id }}</span>
          </div>

          <div class="text-left pl-4 pt-3">
            <p
              class="text-base font-medium text-blue-700 mr-2"
            >#graphicsdesigner #traveller #reader #blogger #digitalmarketer</p>
            <p
              class="text-base font-medium text-gray-700 mr-2"
            >https://www.behance.net/hiravesona7855</p>
          </div>
        </div>

        <div class="w-full md:w-1/4 p-4 text-center">
          <div class="w-full relative md:w-3/4 text-center mt-8">
            <button
              class="flex rounded-full"
              id="user-menu"
              aria-label="User menu"
              aria-haspopup="true"
            >
              <img
                class="rounded-full"
                :src="$page.props.user.profile_photo_url"
                alt
              />
            </button>
          </div>
        </div>
      </div>


      </div>
</template>


<script>
    import {
        defineComponent
    } from 'vue'
    export default defineComponent({
         data() {
            return {
               user : [],
               postCount : '',
            }
        },
        mounted() {
            axios.get('/users/' + this.$page.props.user.name)
            .then(response=>{
                this.user = response.data.user
                this.postCount = response.data.postCount
            })
        }
    })
</script>

