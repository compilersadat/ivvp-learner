<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\TestSeriesController;

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

Route::post('/get-updates',[DataApiController::class,'appUpdate']);
Route::middleware('auth:sanctum')->group(function(){
  Route::get('/home-data',[DataApiController::class,'homeData']);
  Route::get('/prime-content',[DataApiController::class,'primeContent']);
  Route::get('/fetch-exams',[DataApiController::class,'fetchExams']);
  Route::post('/update-profile',[UserController::class,'updateStudent']);
  Route::post('/subscribe-package',[UserController::class,'subscribPackage']);
  Route::post('/update-package',[UserController::class,'updatePackage']);
  Route::get('/user',[AuthController::class,'getUser']);
  Route::post('/start-exam',[ExamController::class,'startExam']);
  Route::post('/submit-exam',[ExamController::class,'submitExam']);
  Route::get('/fetch-result',[ExamController::class,'fetchResult']);
  Route::get('/logout',[AuthController::class,'logout']);
});
Route::post('/phonepe-callback',[UserController::class,'paymentCallback']);

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'signup']);

// Test series
Route::prefix('test-series')->group(function () {
    Route::post('/login',[AuthController::class,'loginTestSeriesUser']);
    Route::post('/register',[AuthController::class,'signupTestSeriesUser']);
    Route::middleware(['auth:sanctum'])->group(function(){
        Route::get('/home-data',[DataApiController::class,'testSeriesHomData']);
        Route::get('/questions/{id}',[TestSeriesController::class,'fetchQuestion']);
    });
});




Route::get('/ajax-get-facualties',function(){
    $branches=App\Models\Faculty::get();
    return response()->json($branches);
})->name('ajax-get-facualties');

Route::get('/ajax-get-languages',function(){
    $branches=App\Models\Language::get();
    return response()->json($branches);
})->name('ajax-get-languages');

Route::get('/ajax-get-locations',function(){
    $branches=App\Models\District::get();
    return response()->json($branches);
})->name('ajax-get-locations');

Route::get('/ajax-get-list-of-collages',function(){
    $dist = FacadesRequest::get('district');
    $ids=App\Models\District::where('name',$dist)->pluck('id');
    $branches=App\Models\Collage::whereIn('district',$ids)->get();
    return response()->json($branches);
})->name('ajax-get-list-of-collages');


Route::get('/ajax-get-list-of-instructors',function(){
    $fact = FacadesRequest::get('iti');

    $branches=App\Models\Instructor::where('iti',$fact)->get();
    return response()->json($branches);
})->name('ajax-get-list-of-instructors');

Route::get('/ajax-get-trends',function(){
    $fact = FacadesRequest::get('fac');
    $branches=App\Models\Branch::where('wrtf',$fact)->get();
    return response()->json($branches);
})->name('ajax-get-trends');

Route::get('/ajax-get-years',function(){
    $fact = FacadesRequest::get('fac');
    $years=App\Models\Faculty::where('faculty_id',$fact)->first();
    return response()->json($years);
})->name('ajax-get-years');
