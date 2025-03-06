<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileShareController;
use App\Http\Controllers\FileStarredController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

});


Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/resend-verify-email', [AuthController::class,'resendVerifyEmail']);

    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user(),200);
    });

    Route::post('/update-password', [UserController::class,'updatePassword']);

    Route::post('/update-info', [UserController::class,'updateInfo']);

    Route::post('/delete-user', [UserController::class,'deleteUser']);

    Route::post('/create-folder', [FileController::class,'createFolder']);

    Route::post('/get-files', [FileController::class,'getFiles']);

    Route::post('/upload-files', [FileController::class,'uploadFiles']);

    Route::post('/get-ancestors', [FileController::class,'getAncestors']);

    Route::post('/download-files', [FileController::class,'downloadFiles']);

    Route::post('/trash-files', [FileController::class,'destroy']);

    Route::post('/get-deleted-files', [FileController::class,'getDeletedFiles']);

    Route::post('/delete-files', [FileController::class,'deleteFiles']);

    Route::post('/restore-files', [FileController::class,'restoreFiles']);

    Route::post('/share-files', [FileShareController::class,'shareFiles']);

    Route::post('/get-shared-files-with-me', [FileShareController::class,'getSharedFilesWithMe']);

    Route::post('/get-shared-files-by-me', [FileShareController::class,'getSharedFilesbyMe']);

    Route::post('/delete-share-by-me', [FileShareController::class,'deleteShareByMe']);

    Route::post('/delete-share-with-me', [FileShareController::class,'deleteShareWithMe']);

    Route::post('/add-to-my-favorites', [FileStarredController::class,'addToMyFavorites']);

    Route::post('/get-my-favorite-files', [FileStarredController::class,'getMyFavoriteFiles']);

    Route::post('/delete-my-favorites', [FileStarredController::class,'deleteMyFavorites']);

    Route::get('/search', [FileController::class,'search']);

});





