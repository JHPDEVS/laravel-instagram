<template>
<div>
<button @click="openModal" class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">글쓰기</button>
</div>
    <div v-for="(post,i) in posts" v-bind:key="i" class="rounded overflow-hidden shadow-lg">
        <img v-if="post.image" class="w-full" v-bind:src="`/storage/images/${post.image}`" :alt="post.image">
        <div class="px-6 py-4">
            <button @click="show(post.id)" class="font-bold text-xl mb-2 text-red-500">{{ post.title }}</button>
            <button @click="show(post.id)" class="bg-transparent hover:bg-blue-500 text-gray-700 font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">상세보기</button>
            <button @click="edit(post.id)"
                class="bg-blue-500 hover:bg-blue-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">수정</button>
            <button @click="showDel(post.id)"
                class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">삭제</button>
            <p class="text-gray-700 text-base">
                {{ post.content }}
            </p>
        </div>
        <div class="px-6 pt-4 pb-2">
            <span
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ post.name }}</span>
            <span
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ post.updated_at }}</span>
        </div>
    </div>

    <!-- 게시글 작성 모달달 -->
    <Dialog :show="dialogShow">

        <template #title>
            게시글 작성
        </template>

        <template #content>
            <div class="flex flex-col space-y-8 mt-10">
                <input type="text" v-model="title" placeholder="제목"
                    class="border rounded-lg py-3 px-3  border-gray-700 placeholder-gray-500">
                <span v-if="msg.title" class="font-medium tracking-wide text-red-500 ">{{ msg.title }}</span>
                <input type="text" v-model="content" placeholder="내용"
                    class="border rounded-lg py-3 px-3  border-gray-700 placeholder-gray-500">
                <span v-if="msg.content" class=" font-medium tracking-wide text-red-500 ">{{ msg.content }}</span>
                <input type="file" @change="upload"
                    class="border rounded-lg py-3 px-3 bg-white-700 border-gray-700 placeholder-gray-500">

                <div v-if="imageFile" class="w-64 h-36 carousel rounded-box carousel-vertical">
                    <div id="preview" class="w-1/2 carousel-item">
                        <img :src="url" />
                    </div>
                </div>
                <span v-if="msg.imageFile" class=" font-medium tracking-wide text-red-500 ">{{ msg.imageFile }}</span>
                <button @click="save"
                    class="border border-blue-500 bg-blue-500 text-white rounded-lg py-3 font-semibold">작성</button>
            </div>
        </template>

        <template #footer>

            <button @click="disabled">닫기</button>

        </template>

        <!-- 게시글 상세보기 댓글 포함함-->
    </Dialog>

    <Dialog :show="showPost">

        <template #title>
            {{ currentPost[0].title }}
        </template>

        <template #content>
            <img v-if="currentPost[0].image" class="w-full" v-bind:src="`/storage/images/${currentPost[0].image}`"
                :alt="currentPost[0].image">
            {{ currentPost[0].content }}

                            <div
                    class="relative flex items-center self-center min-w-full max-w-xl p-4 overflow-hidden text-gray-600 focus-within:text-gray-400">
                    <img class='w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer' alt='photo'
                        :src="$page.props.user.profile_photo_url">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6">
                        <button v-on:click="commentCreate(currentPost[0].id)"
                            class="p-1 focus:outline-none focus:shadow-none hover:text-blue-500 font-xs">
                            작성
                        </button>
                    </span>
                    <input type="search" @keyup.enter="commentCreate(currentPost[0].id)" v-model="commentText"
                        class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 border border-transparent appearance-none rounded-tg placeholder-gray-400 focus:bg-white focus:outline-none focus:border-blue-500 focus:text-gray-900 focus:shadow-outline-blue"
                        style="border-radius: 25px, white-space: pre" placeholder="댓글을 남겨보세요" autocomplete="off">
                </div>
              <div class="flex items-center justify-start bg-white dark:bg-gray-800 min-w-full"
                    v-for="(comment) in comments" v-bind:key="comment.id" style="word-break: break-word;">
                    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-200 p-4 antialiased flex max-w-lg">
                        
                        <img v-if="comment.profile_photo_path" class="w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer" :src="`/storage/${comment.profile_photo_path}`" />
                        <img v-else class="w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer" :src="`https://ui-avatars.com/api/?name=${comment.name}&color=7F9CF5&background=EBF4FF`" />
                        
                        <div v-if="!(commentID == comment.id)">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                                <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                                <div class="text-normal leading-snug md:leading-normal resize">{{ comment.content }}
                                </div>

                            </div>
                            <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-edit fa-xl text-success hover:text-green-300" 
                                    v-on:click="editLayout(comment.id)"></i>
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-trash-alt fa-xl px-2 text-error hover:text-red-300"
                                    v-on:click="opencommentdel(comment.id)"></i>
                            </div>
                        </div>
                        <div v-else>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                                <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                                <textarea class="text-normal leading-snug md:leading-normal resize"
                                    v-model="comment.content" cols="17" rows="5"></textarea>
                            </div>
                            <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-xl fa-window-close text-error hover:text-red-300"
                                    v-on:click="closeEditComment"></i>
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-check fa-xl px-2 text-success hover:text-green-300"
                                    v-on:click="editComment(comment.id,comment.content)"></i>
                            </div>
                        </div>
                    </div>
                </div>
        </template>

        <template #footer>

            <button @click="disabled">닫기</button>

        </template>
    </Dialog>

    <!-- 게시글 수정 -->
    <Dialog :show="editStatus">

        <template #title>
            게시글 수정
        </template>

        <template #content>

            <div class="flex flex-col space-y-8 mt-10">
                <input type="text" v-model="editPost[0].title" placeholder="제목"
                    class="border rounded-lg py-3 px-3 border-gray-700 placeholder-gray-500">
                <span v-if="msg2.title" class="font-medium tracking-wide text-red-500 ">{{ msg2.title }}</span>
                <input type="text" v-model="editPost[0].content" placeholder="내용"
                    class="border rounded-lg py-3 px-3  border-gray-700 placeholder-gray-500">
                <span v-if="msg2.content" class=" font-medium tracking-wide text-red-500 ">{{ msg2.content }}</span>
                <input v-if="!editPost[0].image" type="file" @change="editUpload"
                    class="border rounded-lg py-3 px-3 bg-white-700 border-gray-700 placeholder-gray-500">
                <img v-if="editPost[0].image" class="w-full" v-bind:src="`/storage/images/${editPost[0].image}`"
                    :alt="editPost[0].image">
                <button v-if="editPost[0].image" @click="editPostImageDelete"
                    class="bg-transparent hover:bg-blue-500 text-gray-700 font-semibold hover:text-white py-2 mx-2 px-2 border border-gray-600 hover:border-transparent rounded">이미지
                    삭제</button>
                <div v-if="editImageFile" class="w-64 h-36 carousel rounded-box carousel-vertical">
                    <div id="preview" class="w-1/2 carousel-item">
                        <img :src="url2" />
                    </div>
                </div>
                <span v-if="msg2.imageFile" class=" font-medium tracking-wide text-red-500 ">{{ msg2.imageFile }}</span>
                <button @click="editSave(editPost[0].id)"
                    class="border border-blue-500 bg-blue-500 text-white rounded-lg py-3 font-semibold">수정</button>
            </div>
        </template>

        <template #footer>

            <button @click="disabled">닫기</button>

        </template>
    </Dialog>


    <!-- 게시글 삭제-->
    <Dialog :show="showDelete">

        <template #title>
            삭제
        </template>

        <template #content>
            삭제하시겠습니까?
        </template>

        <template #footer>
            <button @click="deletePost(delPostId)" class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">삭제</button>
            <button @click="disabled" class="bg-blue-500 hover:bg-blue-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">닫기</button>

        </template>
    </Dialog>
</template>

<script>
    import Dialog from '@/Jetstream/DialogModal'
    export default {
        components: {
            Dialog
        },
        props:['id'],
        data() {
            return {
                dialogShow: false,
                title: '',
                content: '',
                msg: [],
                msg2: [],
                posts: [],
                showPost: false,
                currentPost: [],
                editPost: [],
                imageFile: null,
                editImageFile: null,
                editStatus: false,
                url: '',
                showDelete: false,
                delPostId: '',
                commentText: '',
                comments: [],
            }
        },
        mounted() {
            this.refresh()
        },
        methods: {
            show(id) {
                axios.get('/posts/' + id)
                    .then(response => {
                        this.currentPost = response.data.post
                        this.comments = response.data.comments.data
                        this.showPost = true;
                        this.commentText = ''
                    })
            },
            edit(id) {
                axios.get('/posts/' + id)
                    .then(response => {
                        this.editPost = response.data.post
                        this.editStatus = true;
                        this.editImageFile = ''
                        this.msg2 = ''
                    })
            },
            openModal() {
                this.dialogShow = true
                this.imageFile = ''
                this.title = ''
                this.content = ''
            },
            disabled() {
                this.dialogShow = false;
                this.showPost = false;
                this.editStatus = false;
                this.showDelete = false;
            },
            save() {
                const bodyFormData = new FormData();
                bodyFormData.append('imageFile', this.imageFile);
                bodyFormData.append('user_id', this.$page.props.user.id);
                bodyFormData.append('content', this.content);
                bodyFormData.append('title', this.title);
                axios.post('/posts/save', bodyFormData)
                    .then(response => {
                        if (response.data.status == "false") {
                            this.msg = response.data.data
                        } else {
                            this.msg = ''
                            alert("게시글 작성완료")
                            this.dialogShow = false;
                            this.refresh();
                        }
                    })
                    .catch(error => {
                        alert("에러")
                    })
            },
            refresh() {
                axios.get('posts/index')
                    .then(response => {
                        this.posts = response.data.posts.data
                    })
            },
            commentRefresh(postId) {
                axios.get('/posts/' + postId)
                    .then(response => {
                        this.comments = response.data.comments.data
                    })
            },
            upload(event) {
                this.imageFile = event.target.files[0];
                this.msg.imageFile = ''
                this.url = URL.createObjectURL(this.imageFile);
                if (!this.imageFile) {
                    this.url = ''
                }
            },
            editPostImageDelete() {
                this.editPost[0].image = ''
                this.editImageFile = null
            },
            editUpload(event) {
                this.editImageFile = event.target.files[0];
                if(this.msg2.imageFile) {
                    this.msg2.imageFile = ''
                }
                this.url2 = URL.createObjectURL(this.editImageFile);
                if (!this.editImageFile) {
                    this.url2 = ''
                }
            },
            editSave(postId) {
                const bodyFormData = new FormData();
                if (this.editImageFile) {
                    bodyFormData.append('imageFile', this.editImageFile);
                } else {
                    if(this.editPost[0].image) {
                        bodyFormData.append('imageOk' , true);
                    }
                }
                bodyFormData.append('user_id', this.$page.props.user.id);
                bodyFormData.append('content', this.editPost[0].content);
                bodyFormData.append('title', this.editPost[0].title);
                axios.post('/posts/edit/' + postId, bodyFormData)
                    .then(response => {
                        if (response.data.status == "false") {
                            this.msg2 = response.data.data
                            if (response.data.message) {
                                alert(response.data.message)
                                this.msg2= ''
                                this.disabled()
                            }
                
                        } else {
                            this.msg2 = ''
                            alert("게시글 수정완료")
                            this.editStatus = false;
                            this.refresh();
                        }
                    })
                    .catch(error => {
                        alert("에러")
                    })
            },
            showDel(postId) {
                this.showDelete = true;
                this.delPostId = postId;
            },
            deletePost(postId) {
                 let value = {
                    user_id: this.$page.props.user.id
                }
                axios.delete('/posts/delete/' + postId , {params : value})
                .then(response=> {
                    alert(response.data.message)
                    if(response.data.status=='ture') {
                        this.disabled();
                        this.refresh();
                    }
                })
            },
            commentCreate(postId) {
                const commentFormData = new FormData();
                commentFormData.append('user_id', this.$page.props.user.id)
                commentFormData.append('content', this.commentText)
                axios.post('/posts/comment/make/' + postId , commentFormData)
                .then(response=>{
                    if(response.data.status = "success") {
                        alert("댓글이 작성되었습니다")
                        this.commentRefresh(postId) // 댓글 최신화
                    } else {
                        alert(response.data.message);
                    }
                })
            }
        }
    }
</script>