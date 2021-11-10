<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfilesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/posts/index', [PostsController::class,'index']);
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/{id}', function ($id) {
    return Inertia::render('IDDashboard',['id'=>$id]);
});

Route::get('/users/{id}',[PostsController::class,'getUserInfo']);
Route::get('/posts/index/{id}', [PostsController::class,'nameIndex']);
Route::get('/posts/{post_id}', [PostsController::class,'show']);
Route::post('/posts/save', [PostsController::class,'create']);
Route::delete('/posts/delete/{post_id}', [PostsController::class,'delete']);
Route::post('/posts/edit/{selected_post_id}', [PostsController::class,'edit']);

Route::post('/posts/comment/make/{selected_post_id}/', [CommentsController::class,'create']);
Route::post('/posts/comment/update/{comment}/', [CommentsController::class,'update']);
Route::delete('/posts/comment/delete/{comment}/', [CommentsController::class,'delete']);

Route::post('/posts/like/{postId}/', [LikesController::class,'create']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard',['username'=>auth()->user()->name]);
})->name('dashboard');

Route::apiResource('/profile',ProfilesController::class)->only(['store','destroy','update']);