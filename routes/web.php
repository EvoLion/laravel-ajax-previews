<?php

use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ImageUploadController::class, 'index'])->name('image-upload-preview');
Route::post('/preview-image', [ImageUploadController::class, 'save'])->name('preview-image');
Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload-image');
Route::post('/delete-image', [ImageUploadController::class, 'delete'])->name('delete-image');