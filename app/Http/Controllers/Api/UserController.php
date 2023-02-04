<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentPackage;
use Razorpay\Api\Api;
use App\Models\Transaction;

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
        $api = new Api(env("RAZOR_PAY_KEY"), env("RAZOR_PAY_SECRETE"));
        $response=$api->order->create(array('receipt' => md5(mt_rand()), 'amount' => $request->price, 'currency' => 'INR'));
        if($response){
            $transaction=new Transaction();
            $transaction->student_id=$request->student_id;
            $transaction->package_id=$request->package_id;
            $transaction->package_name=$request->package_name;
            $transaction->number_of_months=$request->number_of_month;
            $transaction->price=$request->price;
            $transaction->order_id=$response['id'];
            $transaction->reciept=$response['receipt'];
            $transaction->status=$response['status'];
            if($transaction->save()){
                $responses['message']="Order Created.";
                $responses['subscription_details']=$transaction;
                return $this->sendResponse($responses);
             }else{
                $error = "Sorry! Please try again";
                return $this->sendError($error, 401);
            }
        }
}

public function updatePackage(Request $request){
    $validator = Validator::make($request->all(), [
        'order_id'=>'required',
       'transaction_id'=>'required'
    ]);
    if($validator->fails()){
        return $this->sendError($validator->errors());
    }
    $transaction=Transaction::where('order_id',$request->order_id)->first();

    $transaction->status="compeleted";
    $transaction->transaction_id=$request->transaction_id;
    $transaction->update();
    $count=StudentPackage::where('student_id',$request->student_id)->count();
        $student_package=new StudentPackage();
        if($count){
            $student_package=StudentPackage::where('student_id',$request->student_id)->first();
        }
        $student_package->student_id=$transaction->student_id;
        $student_package->package_id=$transaction->package_id;
        $student_package->package_name=$transaction->package_name;
        $student_package->number_of_months=$transaction->number_of_month;
        $student_package->price=$transaction->price;
        $student_package->start_date=date('d-m-y');
        $student_package->start_month=date('m');
        $student_package->status=2;
        if($count==0?$student_package->save():$student_package->update()){
            $response['message']="Subscription Activated";
            $response['subscription_details']=$student_package;
            return $this->sendResponse($response);
        }else{
            $error = "Sorry! Please try again";
            return $this->sendError($error, 401);
        }
}
}
