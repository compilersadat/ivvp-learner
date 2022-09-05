<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\S3uploadController;
use App\Http\Controllers\Admin\SliderController;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class,'index'])->name('home');


Route::prefix('admin')->group(function () {
    Route::get('contents',[ContentController::class,'index'])->name('content.index');
    Route::get('contents/create',[ContentController::class,'create'])->name('content.create');
    Route::post('contents/store',[ContentController::class,'store'])->name('content.store');

    Route::get('uploads',[S3uploadController::class,'index'])->name('upload.index');
    Route::get('uploads/create',[S3uploadController::class,'create'])->name('upload.create');
    Route::post('uploads/store',[S3uploadController::class,'store'])->name('upload.store');

    Route::get('sliders',[SliderController::class,'index'])->name('slider.index');
    Route::get('sliders/create',[SliderController::class,'create'])->name('slider.create');
    Route::post('sliders/store',[SliderController::class,'store'])->name('slider.store');

});



Route::get('ckeditor', [CkeditorController::class,'index']);
Route::get('edit/ckeditor', [CkeditorController::class,'editIndex']);
Route::post('ckeditor/upload', [CkeditorController::class,'upload'])->name('ckeditor.upload');
