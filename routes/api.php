<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/rol',RolController::class);
Route::apiResource('/user',UserController::class);
Route::apiResource('/comment',CommentController::class);
Route::apiResource('/article',ArticleController::class);
Route::post('article/actualizar/{id}',[ArticleController::class,'actualizar']);


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/


/*Route::apiResource('/articles', ArticleController::class);
Route::get ('/rol',[Rolcontroller::class],'index');
*/


