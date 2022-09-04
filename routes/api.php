<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as FacadesRequest;

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
