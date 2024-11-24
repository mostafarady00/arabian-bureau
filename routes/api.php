<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Company_profileController;
use App\Http\Controllers\Api\ContactusController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\TrainingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});






// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Company Profile routes
    Route::get('/company-profiles', [Company_profileController::class, 'index']);
    Route::get('/company-profiles/{id}', [Company_profileController::class, 'show']);
    Route::post('/company-profiles', [Company_profileController::class, 'store']);
    Route::post('/company-profiles/{id}', [Company_profileController::class, 'update']);
    Route::delete('/company-profiles/{id}', [Company_profileController::class, 'destroy']);

    // About routes
    Route::get('/about', [AboutController::class, 'index']);
    Route::get('/about/{id}', [AboutController::class, 'show']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::post('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);

    // Inspections routes
    Route::apiResource('inspections', InspectionController::class);
    Route::post('/inspections/{id}', [InspectionController::class, 'update']);
    // Training routes
    Route::apiResource('Training', TrainingController::class);
    Route::post('/Training/{id}', [TrainingController::class, 'update']);

    // Approvals routes
    Route::apiResource('Approvals', ApprovalController::class);
    Route::post('/Approvals/{id}', [ApprovalController::class, 'update']);

    // Gallery routes
    Route::apiResource('Gallery', GalleryController::class);
    Route::post('/Gallery/{id}', [GalleryController::class, 'update']);

    // Contact Us routes
    Route::apiResource('ContactUs', ContactusController::class);
});
