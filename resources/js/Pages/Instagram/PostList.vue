<template>
    <div>
        <button v-if="!user_id || user_id == $page.props.user.id" @click="openModal"
            class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">글쓰기</button>
    </div>
    <div v-if="!posts[0]">
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
                    <span class="text-gray-500 text-6xl block"><span>게시글 없음</span></span>
                    <span class="text-gray-500 text-xl">작성한 게시글이 없습니다.</span>

                </div>
            </center>
        </div>
    </div>
    <div v-for="(post,i) in posts" v-bind:key="i" class="rounded overflow-hidden shadow-lg">
        <img v-if="post.image" class="w-full" v-bind:src="`/storage/images/${post.image}`" :alt="post.image">
        <div class="px-6 py-4">
            <button @click="show(post.id)" class="font-bold text-xl mb-2 text-red-500">{{ post.title }}</button>
            <button @click="show(post.id)"
                class="bg-transparent hover:bg-blue-500 text-gray-700 font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">상세보기</button>
            <button @click="edit(post.id)"
                class="bg-blue-500 hover:bg-blue-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">수정</button>
            <button @click="showDel(post.id)"
                class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">삭제</button>
            <p class="text-gray-700 text-base">
                {{ post.content }}
            </p>
        </div>
        <div class="px-6 pt-4 pb-2">
                                  <button @click=like(post.id)
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">좋아요 </button>
                      <span
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">댓글 : {{ post.comments_count }} 개</span>
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
                    <a :href="`/dashboard/${comment.user_id}`">
                        <img v-if="comment.profile_photo_path"
                            class="w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer"
                            :src="`/storage/${comment.profile_photo_path}`" />
                        <img v-else class="w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer"
                            :src="`https://ui-avatars.com/api/?name=${comment.name}&color=7F9CF5&background=EBF4FF`" />
                    </a>


                    <div v-if="!(commentID == comment.id)">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                            <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                            <div class="text-normal leading-snug md:leading-normal resize">{{ comment.content }}
                            </div>

                        </div>
                        <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                            <i v-if="$page.props.user.id == comment.user_id"
                                class="text-success hover:text-green-300"
                                v-on:click="editLayout(comment.id)">수정</i>
                            <i v-if="$page.props.user.id == comment.user_id"
                                class="px-2 text-error hover:text-red-300"
                                v-on:click="opencommentdel(comment.id)">삭제</i>
                        </div>
                    </div>
                    <div v-else>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                            <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                            <textarea class="text-normal leading-snug md:leading-normal resize"
                                v-model="comment.content" cols="17" rows="5"></textarea>
                        </div>
                        <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                            <i v-if="$page.props.user.id == comment.user_id"
                                class="fas fa-xl fa-window-close text-error hover:text-red-300"
                                v-on:click="closeEditComment">취소</i>
                            <i v-if="$page.props.user.id == comment.user_id"
                                class="fas fa-check fa-xl px-2 text-success hover:text-green-300"
                                v-on:click="editComment(comment.id,comment.content)">수정</i>
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
            <button @click="deletePost(delPostId)"
                class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">삭제</button>
            <button @click="disabled"
                class="bg-blue-500 hover:bg-blue-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">닫기</button>

        </template>
    </Dialog>

     <!-- 댓글 삭제-->
    <Dialog :show="showCommentDelete">

        <template #title>
            삭제
        </template>

        <template #content>
            댓글 삭제하시겠습니까?
        </template>

        <template #footer>
            <button @click="deleteComment(deleteCommentID)"
                class="bg-red-500 hover:bg-red-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">삭제</button>
            <button @click="disabled"
                class="bg-blue-500 hover:bg-blue-300 text-white font-semibold hover:text-white py-2 mx-1 px-2 border border-gray-600 hover:border-transparent rounded">닫기</button>

        </template>
    </Dialog>
</template>

<script>
    import Dialog from '@/Jetstream/DialogModal'
    export default {
        components: {
            Dialog
        },
        props: ['user_id'],
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
                commentID: '',
                showCommentDelete: false,
                deleteCommentID: '',
                selPost : '',
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
                        this.selPost = id;
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
                if (this.user_id) {
                    axios.get('/posts/index/' + this.user_id)
                        .then(response => {
                            this.posts = response.data.posts.data
                        })
                } else {
                    axios.get('/posts/index')
                        .then(response => {
                            this.posts = response.data.posts.data
                        })
                }
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
                if (this.msg2.imageFile) {
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
                    if (this.editPost[0].image) {
                        bodyFormData.append('imageOk', true);
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
                                this.msg2 = ''
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
                axios.delete('/posts/delete/' + postId, {
                        params: value
                    })
                    .then(response => {
                        alert(response.data.message)
                        if (response.data.status == 'ture') {
                            this.disabled();
                            this.refresh();
                        }
                    })
            },
            commentCreate(postId) {
                const commentFormData = new FormData();
                commentFormData.append('user_id', this.$page.props.user.id)
                commentFormData.append('content', this.commentText)
                axios.post('/posts/comment/make/' + postId, commentFormData)
                    .then(response => {
                        if (response.data.status = "success") {
                            alert("댓글이 작성되었습니다")
                            this.commentRefresh(postId) // 댓글 최신화
                        } else {
                            alert(response.data.message);
                        }
                    })
            },
            deleteComment(commentID) {
                axios.delete('/posts/comment/delete/' + commentID)
                    .then(response => {
                        alert("삭제 되었습니다")
                        this.refresh();
                        this.commentRefresh(this.selPost)
                        this.showCommentDelete = false
                    })
            },
            editComment(commentID, content) {
                axios.post('/posts/comment/update/' + commentID, {
                        content: content
                    })
                    .then(response => {
                        alert("수정 되었습니다")
                        this.closeEditComment()
                    })
            },
            editLayout(commentID) {
                this.refresh()
                this.commentID = commentID;
            },
            opencommentdel(commentID) {
                this.showCommentDelete = true
                this.deleteCommentID = commentID;
            },
            closeEditComment() {
                this.commentID = '';
                this.refresh();
            },
            like(postId) {
                const likeFormData = new FormData();
                likeFormData.append('user_id', this.$page.props.user.id)
                axios.post('/posts/like/' + postId , likeFormData)
                .then(response=>{
                    alert(response.data.msg);
                })
            }
        }
    }
</script>