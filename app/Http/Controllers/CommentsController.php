<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
class CommentsController extends Controller
{
    public function create(Request $req,$postId) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        $comment = new Comment();
        $comment->user_id = $req->user_id;
        $comment->post_id = $postId;
        $comment->content = $req->content;

        $comment->save();

        return response()->json([
            'status' => 'success',
            'comment' => $comment,
        ], 200);
    }

     // 댓글 삭제
     public function delete(Comment $comment) {
        $comment->delete();

        return response()->json([
            'status' => 'success',
        ], 200);
    }

      // 댓글 수정
      public function update(Request $req, Comment $comment) {
        $validator = Validator::make($req->all(), [
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }

        $comment->content = $req->content;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'comment' => $comment,
        ], 200);
    }
}
