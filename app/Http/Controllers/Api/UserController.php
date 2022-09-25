<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\Student;
class UserController extends Controller
{
    //
    public function updateStudent(Request $request){
        $student=Student::where('id',$request->id)->first();
        $student->name=$request->name;
        $student->phone=$request->phone;
        $student->faculty=$request->faculty;
        $student->branch=$request->branch;
        $student->collage=$request->collage;
        $student->district=$request->district;
        $student->year=$request->year;
        $student->m_toung=$request->m_toung;
        if($student->update()){
            return $this->sendResponse($student);

        }else{
            $error = "Sorry! Registration is not successfull.";
            return $this->sendError($error, 401);
        }




    }
}
