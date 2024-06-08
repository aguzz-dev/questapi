<?php
require_once '../autoload.php';

use Lib\Route;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\PublicPostController;
use App\Controllers\QuestionController;
use App\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-session', [AuthController::class, 'checkSession']);
Route::post('/destroy-session', [AuthController::class, 'destroyToken']);

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts/create', [PostController::class, 'store']);
Route::put('/posts', [PostController::class, 'update']);
Route::delete('/posts', [PostController::class, 'destroy']);

Route::post('/user/create', [UserController::class, 'store']);
Route::put('/user/update', [UserController::class, 'update']);
Route::delete('/user/destroy', [UserController::class, 'destroy']);

Route::post('/change-password', [UserController::class, 'changePassword']);

Route::post('/share-post', [PublicPostController::class, 'makePublicPost']);
Route::post('/hide-post', [PublicPostController::class, 'makePrivatePost']);

Route::post('/question/create', [QuestionController::class, 'store']);
Route::post('/question/answer', [QuestionController::class, 'answerQuestion']);



Route::dispatch();
