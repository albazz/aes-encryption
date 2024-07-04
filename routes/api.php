<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/insert', [UserController::class, 'insert'])->name('insert');
    Route::get('/userenc/{id}', [UserController::class, 'userEnc'])->name('UserEnc');
    Route::get('/userencid/{id}', [UserController::class, 'userEncid'])->name('UserEncid');
    Route::get('/userdec/{id}', [UserController::class, 'userDec'])->name('UserDec');
    Route::get('/userdecid/{id}', [UserController::class, 'userDecid'])->name('UserDecid');
    Route::post('/userupdateenc', [UserController::class, 'userUpdateEnc'])->name('UserUpdateEnc');
    Route::post('/deleteuser/{id}', [UserController::class, 'deleteUser'])->name('DeleteUser');
});
