<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostsController extends Controller
{

    public function uploadPostImage($req) {
        // 이름에 시간 넣기
        $name = $req->file('imageFile')->getClientOriginalName();
        $extension = $req->file('imageFile')->extension();
        $nameWithoutExtension = Str::of($name)->basename('.' . $extension);
        $fileName = $nameWithoutExtension . '_' . time() . '.' . $extension;

        // 파일의 경로와 이름 지정
        $req->file('imageFile')->storeAs('public/images', $fileName);

        return $fileName;
    }

    public function getUserInfo($user_name) {
        $user = User::all()->where('name','=',$user_name)->first();

        $postsCount = DB::table('posts')
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->where('users.name', '=', $user_name)
        ->select(
            DB::raw('users.id, COUNT(posts.id) as count')
        )->count();

        $commentsCount = DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->where('users.name', '=', $user_name)
        ->select(
            DB::raw('users.id, COUNT(comments.id) as count')
        )->count();


        $res = response()->json([
            'status' => 'success',
            'user' => $user,
            'postCount' => $postsCount,
            'commentsCount' => $commentsCount
        ],200);

        return $res;
    }
    public function index() {
         
        // 댓글 많은 순
        $posts = Post::withCount('comments')
        ->join('users','users.id','=','posts.user_id')
        ->select(
            DB::raw('posts.id, posts.title,
            posts.content, posts.user_id, posts.image,
            posts.updated_at, users.name'))
        ->orderBy('comments_count', 'desc')
        ->paginate(3);

        $commentsCount = DB::table('comments')
        ->join('posts', 'posts.id', '=', 'comments.post_id')
        ->select(
            DB::raw('posts.id, COUNT(comments.id) as count')
        )->groupBy('posts.id')->orderBy('posts.created_at', 'desc')
        ->get();

        $i = 0;
        $flag = true;

        foreach($posts as $row) {
            $flag = true;

            for ($i = 0; $i < $commentsCount->count(); $i++) {
                if ($row->id == $commentsCount[$i]->id) {
                    $row->comments_count = $commentsCount[$i]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->comments_count = null;
            }
        }

        $likesCount = DB::table('likes')
        ->join('posts', 'posts.id', '=', 'likes.post_id')
        ->select(
            DB::raw('posts.id, COUNT(likes.id) as count')
        )->groupBy('posts.id')->orderBy('posts.created_at', 'desc')
        ->get();

        $i = 0;
        $flag = true;

        foreach($posts as $row) {
            $flag = true;

            for ($i = 0; $i < $likesCount->count(); $i++) {
                if ($row->id == $likesCount[$i]->id) {
                    $row->likesCount = $likesCount[$i]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->likesCount = null;
            }
        }

        
        $res = response()->json([
            'status' => 'success',
            'posts' => $posts
        ],200);

        return $res;
    }

    public function nameIndex($id) {

        $user_id = DB::table('users')->where('name', $id)->first()->id;

        $posts = DB::table('posts')
        ->join('users','users.id','=','posts.user_id')
        ->where('posts.user_id', '=', $user_id)
        ->select(
            DB::raw('posts.id, posts.title,
            posts.content, posts.user_id, posts.image,
            posts.updated_at, users.name'),
        )->orderBy('posts.id', 'desc')->paginate(3);
        
          $commentsCount = DB::table('comments')
        ->join('posts', 'posts.id', '=', 'comments.post_id')
        ->select(
            DB::raw('posts.id, COUNT(comments.id) as count')
        )->groupBy('posts.id')->orderBy('posts.created_at', 'desc')
        ->get();

        $i = 0;
        $flag = true;

        foreach($posts as $row) {
            $flag = true;

            for ($i = 0; $i < $commentsCount->count(); $i++) {
                if ($row->id == $commentsCount[$i]->id) {
                    $row->comments_count = $commentsCount[$i]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->comments_count = null;
            }
        }
        
        $likesCount = DB::table('likes')
        ->join('posts', 'posts.id', '=', 'likes.post_id')
        ->select(
            DB::raw('posts.id, COUNT(likes.id) as count')
        )->groupBy('posts.id')->orderBy('posts.created_at', 'desc')
        ->get();

        $i = 0;
        $flag = true;

        foreach($posts as $row) {
            $flag = true;

            for ($i = 0; $i < $likesCount->count(); $i++) {
                if ($row->id == $likesCount[$i]->id) {
                    $row->likesCount = $likesCount[$i]->count;
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                $row->likesCount = null;
            }
        }
        $res = response()->json([
            'status' => 'success',
            'posts' => $posts,
            'user_id' => $user_id,
        ],200);

        return $res;
    }
    // 자유 게시판 생성
    public function create(Request $req) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'required|string',
            'imageFile' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        $post = new Post();
        $post->user_id = $req->user_id;
        $post->title = $req->title;
        $post->content = $req->content;
        if ($req->file('imageFile')) {
            $post->image = $this->uploadPostImage($req);
        }
        $post->save();

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
        ], 200);

        return $res;
    }
    public function delete(Request $req,$selected_post_id) {


        $post = Post::find($selected_post_id);

        if ($req->user_id != $post->user_id) {
            $res = response()->json([
                'status' => 'false',
                'message' => '작성자가 아닙니다.' , $req->user_id,
            ], 200, [], JSON_UNESCAPED_UNICODE);

            return $res;
        }

        if ($post->image) {
            $imagePath = 'public/images/' . $post->image;
            Storage::delete($imagePath);
        }

        $post->delete();

        $res = response()->json([
            'status' => 'ture',
            'message' => '삭제가 되었습니다.',
        ], 200, [], JSON_UNESCAPED_UNICODE);

        return $res;
    }
    public function edit(Request $req,$selected_post_id) {
        $post = Post::find($selected_post_id);

        if(($req->imageFile == $post->image)) {
            $validator = Validator::make($req->all(), [
                'title' => 'required|string',
                'content' => 'required|string',
            ]);
            $req->imageFile = $post->image;
        } else {
            $validator = Validator::make($req->all(), [
                'title' => 'required|string',
                'content' => 'required|string',
                'imageFile' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
        }

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        if ($req->user_id != $post->user_id) {
            $res = response()->json([
                'status' => 'false',
                'message' => '작성자가 아닙니다.',
            ], 200, [], JSON_UNESCAPED_UNICODE);

            return $res;
        }

        if ($req->file('imageFile')) {
            $imagePath = 'public/images/' . $post->image;
            Storage::delete($imagePath);
            $post->image = $this->uploadPostImage($req);
        } else {
            $imagePath = 'public/images/' . $post->image;
        }

        if(!$req->file('imageFile')) {
            if(!$req->imageOk) {
                $imagePath = 'public/images/' . $post->image;
                Storage::delete($imagePath);
                $post->image = '';
            }
        }
        $post->title = $req->title;
        $post->content = $req->content;

        $post->save();

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
        ], 200);

        return $res;
    }
    // 게시판 상세보기
    public function show($selected_post_id) {
        $post = DB::table('posts')
        ->join('users', 'users.id', '=', 'posts.user_id')
        ->where('posts.id', '=', $selected_post_id)
        ->select(
            DB::raw('posts.id, posts.title, posts.content,
            posts.user_id, posts.image,
            posts.updated_at, users.name, users.profile_photo_path'),
        )
        ->get();

        $comment = DB::table('comments')
        ->join('users', 'users.id', '=', 'comments.user_id')
        ->where('post_id', '=', $selected_post_id)
        ->select(
            DB::raw('comments.id, comments.content, users.name, comments.post_id,
            comments.created_at, comments.updated_at, comments.user_id,
            users.profile_photo_path')
        )
        ->orderBy('comments.id', 'desc')->paginate(10);

        foreach($comment as $row) {
            $row->updated_at = Carbon::parse($row->updated_at);
            $row->updated_at = $row->updated_at->diffForHumans(Carbon::now());
        }

    

        $res = response()->json([
            'status' => 'success',
            'post' => $post,
            'comments' => $comment,
        ]);

        return $res;
    }
}   
