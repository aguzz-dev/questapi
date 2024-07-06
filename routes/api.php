<?php
require_once '../autoload.php';

use Lib\Route;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\AssetController;
use App\Controllers\QuestionController;
use App\Controllers\PublicPostController;

Route::get('', function(){});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-session', [AuthController::class, 'checkSession']);
Route::post('/destroy-session', [AuthController::class, 'logout']);

Route::post('/posts', [PostController::class, 'index']);
Route::post('/posts/create', [PostController::class, 'store']);
Route::put('/posts', [PostController::class, 'update']);
Route::delete('/posts', [PostController::class, 'destroy']);
Route::post('/posts/questions', [PostController::class, 'getPostByIdWithQuestions']);

Route::post('/user/create', [UserController::class, 'store']);
Route::put('/user/update', [UserController::class, 'update']);
Route::delete('/user/destroy', [UserController::class, 'destroy']);

Route::post('/change-password', [UserController::class, 'changePassword']);

Route::post('/share-post', [PublicPostController::class, 'makePublicPost']);
Route::post('/hide-post', [PublicPostController::class, 'makePrivatePost']);

Route::post('question/get', [QuestionController::class, 'getQuestionById']);
Route::post('/question', [QuestionController::class, 'getQuestionsByPostId']);
Route::post('/question/create', [QuestionController::class, 'store']);
Route::post('/question/create-web', [QuestionController::class, 'storeQuestionFromWeb']);
Route::post('/question/answer', [QuestionController::class, 'answerQuestion']);

Route::post('/assets/buy', [AssetController::class, 'buyAsset']);
Route::post('/assets/check', [AssetController::class, 'checkAssetExpired']);
Route::post('/assets/id', [AssetController::class, 'getUserAssetsByUserId']);
Route::post('/assets', [AssetController::class, 'getAllAssets']);

Route::dispatch();
