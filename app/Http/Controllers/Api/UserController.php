<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentPackage;
class UserController extends ResponseController
{
    //
    public function updateStudent(Request $request){
        $student=Student::where('id',$request->id)->first();
        $student->name=$request->name;
        $student->phone=$request->phone;
        $student->collage=$request->collage;
        $student->district=$request->district;
        $student->year=$request->year;
        $student->m_toung=$request->m_toung;
        if($student->update()){
            $response['message']="Profile Updated successfully";
            $response['student']=$student;
            return $this->sendResponse($response);

        }else{
            $error = "Sorry! Please try again";
            return $this->sendError($error, 401);
        }




    }

    public function subscribPackage(Request $request){
        $validator = Validator::make($request->all(), [
            'student_id'=>'required',
            'package_name'=>'required',
            'number_of_month'=>'required',
            'price'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
     $student_package=new StudentPackage();
     $student_package->student_id=$request->student_id;
     $student_package->package_id=$request->package_id;
     $student_package->package_name=$request->package_name;
     $student_package->number_of_month=$request->number_of_month;
     $student_package->price=$request->price;
     $student_package->status=1;
     if($student_package->save()){
        $response['message']="Profile Updated successfully";
        $response['subscription_details']=$student_package;
        return $this->sendResponse($response);
     }else{
        $error = "Sorry! Please try again";
        return $this->sendError($error, 401);
    }
}

public function updatePackage(Request $request){
    $validator = Validator::make($request->all(), [
        'student_id'=>'required',
        'package_name'=>'required',
        'package_id'=>'required',
        'number_of_month'=>'required',
        'price'=>'required',
        'payment'=>'required',
    ]);
    if($validator->fails()){
        return $this->sendError($validator->errors());
    }
 $student_package=StudentPackage::where('student_id',$request->student_id)->first();
 $student_package->student_id=$request->student_id;
 $student_package->package_id=$request->package_id;
 $student_package->package_name=$request->package_name;
 $student_package->number_of_month=$request->number_of_month;
 $student_package->price=$request->price;
 $student_package->status=1;
 if($request->payment==true){
    $student_package->start_date=date('d-m-y');
    $student_package->start_month=date('m');
    $student_package->status=2;

 }

 if($student_package->update()){
    $response['message']="Profile Updated successfully";
    $response['subscription_details']=$student_package;
    return $this->sendResponse($response);
 }else{
    $error = "Sorry! Please try again";
    return $this->sendError($error, 401);
}
}
}
