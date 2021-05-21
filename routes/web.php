<?php

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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\FileController;

Route::post('file/upload', 'FileController@store')->name('file.upload');
Route::post('upload', [FileController::class,'upload'])->name('upload');
Route::get('dropzone/fetch_image', [FileController::class,'fetch_image'])->name('dropzone.fetch_image');

Route::get('dropzone/delete_image', [FileController::class,'delete_image'])->name('dropzone.delete_image');
