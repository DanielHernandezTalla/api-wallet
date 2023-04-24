<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
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

// Route::get('/', [UserController::class, 'auth'])->name('login');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('cerrar', [AuthController::class, 'cerrar']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::resource('users', UserController::class)->except(['create', 'edit'])->middleware('auth');

Route::resource('sections', SectionController::class)->except(['create', 'edit'])->middleware('auth');

Route::resource('icons', IconController::class)->except(['create', 'edit'])->middleware('auth');

Route::resource('categories', CategoryController::class)->except(['create', 'edit'])->middleware('auth');

Route::resource('movements', MovementController::class)->except(['create', 'edit'])->middleware('auth');
