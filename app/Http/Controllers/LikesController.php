<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{
    public function create(Request $req,$postId) {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 200);
        }
        
        $like = DB::table('likes')
        ->where('user_id', '=', $req->user_id)
        ->where('post_id','=',$postId)
        ->first();

        if(!$like) {
            $like = new Like();
            $like->user_id = $req->user_id;
            $like->post_id = $postId;
            $like->save();
        } else {
            return response()->json([
                'status' => 'false',
                'like' => $like,
                'req' => $req->user_id,
                'msg' => "이미 좋아요 하셨어요",
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'msg' => "좋아요 완료",
        ], 200);
    }
}
