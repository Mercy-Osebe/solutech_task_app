<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Private routes
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('tasks',[TaskController::class,'index']);
    Route::post('task/create',[TaskController::class,'store']);
    Route::get('task/{id}',[TaskController::class,'show']);
    Route::put('task/{id}/edit',[TaskController::class,'update']);
    Route::delete('task/{id}',[TaskController::class,'destroy']);
    Route::post('logout',[UserController::class,'logout']);
});
// public routes
Route::post('login',[UserController::class,'login']);
Route::post('register',[UserController::class,'register']);