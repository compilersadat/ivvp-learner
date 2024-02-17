<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\S3uploadController;
use App\Http\Controllers\Admin\UploadStudentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CollageController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentPurchaseController;
use App\Http\Controllers\Admin\TestSeries\TestSeriesController;
use App\Http\Controllers\Admin\TestSeries\TestSeriesTestController;
use App\Http\Controllers\Admin\TestSeries\TestSeriesQuestionController;

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
    Route::get('contents/delete/{id}',[ContentController::class,'delete'])->name('content.delete');
    Route::get('contents/edit/{id}',[ContentController::class,'edit'])->name('content.edit');
    Route::post('contents/update/{id}',[ContentController::class,'update'])->name('content.update');

    Route::get('students',[StudentController::class,'index'])->name('student.index');
    Route::get('students/edit/{id}',[StudentController::class,'edit'])->name('student.edit');
    Route::get('students/show/{id}',[StudentController::class,'show'])->name('student.show');
    Route::post('students/update/{id}',[StudentController::class,'update'])->name('student.update');

    Route::get('uploads',[S3uploadController::class,'index'])->name('upload.index');
    Route::get('uploads/create',[S3uploadController::class,'create'])->name('upload.create');
    Route::post('uploads/store',[S3uploadController::class,'store'])->name('upload.store');
    Route::get('uploads/delete/{id}',[S3uploadController::class,'delete'])->name('upload.delete');

    Route::get('uploadstudents',[uploadStudentController::class,'index'])->name('uploadstudent.Create');
    Route::get('uploadstudents/create',[uploadStudentController::class,'create'])->name('uploadstudent.create');
    Route::post('uploadstudents/store',[uploadStudentController::class,'store'])->name('uploadstudent.store');
    Route::get('/download', [uploadStudentController::class,'download'])->name('uploadstudent.download');



    Route::get('sliders',[SliderController::class,'index'])->name('slider.index');
    Route::get('sliders/create',[SliderController::class,'create'])->name('slider.create');
    Route::post('sliders/store',[SliderController::class,'store'])->name('slider.store');
    Route::get('sliders/delete/{id}',[SliderController::class,'delete'])->name('slider.delete');

    Route::get('packages',[PackageController::class,'index'])->name('packages.index');
    Route::get('packages/create',[PackageController::class,'create'])->name('packages.create');
    Route::post('packages/store',[PackageController::class,'store'])->name('packages.store');
    Route::get('packages/delete/{id}',[PackageController::class,'delete'])->name('packages.delete');
    Route::get('packages/edit/{id}',[PackageController::class,'edit'])->name('packages.edit');
    Route::post('packages/update/{id}',[PackageController::class,'update'])->name('packages.update');


    Route::get('collages',[CollageController::class,'index'])->name('collages.index');
    Route::get('collages/create',[CollageController::class,'create'])->name('collages.create');
    Route::post('collages/store',[CollageController::class,'store'])->name('collages.store');
    Route::get('collages/delete/{id}',[CollageController::class,'delete'])->name('collages.delete');
    Route::get('collages/edit/{id}',[CollageController::class,'edit'])->name('collages.edit');
    Route::post('collages/update/{id}',[CollageController::class,'update'])->name('collages.update');

    Route::get('instructors',[InstructorController::class,'index'])->name('instructors.index');
    Route::get('instructors/create',[InstructorController::class,'create'])->name('instructors.create');
    Route::post('instructors/store',[InstructorController::class,'store'])->name('instructors.store');
    Route::get('instructors/delete/{id}',[InstructorController::class,'delete'])->name('instructors.delete');
    Route::get('instructors/edit/{id}',[InstructorController::class,'edit'])->name('instructors.edit');
    Route::post('instructors/update/{id}',[InstructorController::class,'update'])->name('instructors.update');

    Route::get('exams',[ExamController::class,'index'])->name('exams.index');
    Route::get('exams/create',[ExamController::class,'create'])->name('exams.create');
    Route::post('exams/store',[ExamController::class,'store'])->name('exams.store');
    Route::get('exams/delete/{id}',[ExamController::class,'delete'])->name('exams.delete');
    Route::get('exams/edit/{id}',[ExamController::class,'edit'])->name('exams.edit');
    Route::post('exams/update/{id}',[ExamController::class,'update'])->name('exams.update');
    Route::get('exams/show/{id}',[ExamController::class,'show'])->name('exams.show');
    Route::get('exams/publish/{id}',[ExamController::class,'changeStatus'])->name('exams.changeStatus');

    Route::get('testseries',[TestSeriesController::class,'index'])->name('testseries.index');
    Route::get('testseries/create',[TestSeriesController::class,'create'])->name('testseries.create');
    Route::post('testseries/store',[TestSeriesController::class,'store'])->name('testseries.store');
    Route::get('testseries/delete/{id}',[TestSeriesController::class,'delete'])->name('testseries.delete');
    Route::get('testseries/edit/{id}',[TestSeriesController::class,'edit'])->name('testseries.edit');
    Route::post('testseries/update/{id}',[TestSeriesController::class,'update'])->name('testseries.update');
    Route::get('testseries/publish/{id}',[TestSeriesController::class,'changeStatus'])->name('testseries.changeStatus');
    Route::get('testseries/show/{id}',[TestSeriesController::class,'show'])->name('testseries.show');

    Route::get('test',[TestSeriesTestController::class,'index'])->name('testseries.test.index');
    Route::get('test/create',[TestSeriesTestController::class,'create'])->name('testseries.test.create');
    Route::post('test/store',[TestSeriesTestController::class,'store'])->name('testseries.test.store');
    Route::get('test/delete/{id}',[TestSeriesTestController::class,'delete'])->name('testseries.test.delete');
    Route::get('test/edit/{id}',[TestSeriesTestController::class,'edit'])->name('testseries.test.edit');
    Route::post('test/update/{id}',[TestSeriesTestController::class,'update'])->name('testseries.test.update');
    Route::get('test/publish/{id}',[TestSeriesTestController::class,'changeStatus'])->name('testseries.test.changeStatus');
    Route::get('test/show/{id}',[TestSeriesTestController::class,'show'])->name('testseries.test.show');
   
    Route::get('test/questions/create',[TestSeriesQuestionController::class,'create'])->name('test.questions.create');
    Route::post('test/questions/store',[TestSeriesQuestionController::class,'store'])->name('test.questions.store');
    Route::get('test/questions/delete/{id}',[TestSeriesQuestionController::class,'delete'])->name('test.questions.delete');
    Route::get('test/questions/edit/{id}',[TestSeriesQuestionController::class,'edit'])->name('test.questions.edit');
    Route::post('test/questions/update/{id}',[TestSeriesQuestionController::class,'update'])->name('test.questions.update');
    Route::get('test/questions/show/{id}',[TestSeriesQuestionController::class,'show'])->name('test.questions.show');

    Route::get('questions/create',[QuestionController::class,'create'])->name('questions.create');
    Route::post('questions/store',[QuestionController::class,'store'])->name('questions.store');
    Route::get('questions/delete/{id}',[QuestionController::class,'delete'])->name('questions.delete');
    Route::get('questions/edit/{id}',[QuestionController::class,'edit'])->name('questions.edit');
    Route::post('questions/update/{id}',[QuestionController::class,'update'])->name('questions.update');
    Route::get('questions/show/{id}',[QuestionController::class,'show'])->name('questions.show');
    

});



Route::get('ckeditor', [CkeditorController::class,'index']);
Route::get('edit/ckeditor', [CkeditorController::class,'editIndex']);
Route::post('ckeditor/upload', [CkeditorController::class,'upload'])->name('ckeditor.upload');
