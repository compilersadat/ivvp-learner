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
use Ixudra\Curl\Facades\Curl;


class UserController extends ResponseController
{
    //
    public function updateStudent(Request $request){
        $student=Student::where('id',$request->id)->first();
        $student->name=$request->name;
        $student->phone=$request->phone;
        $student->district=$request->district;
        if($student->update()){
            $response['message']="Profile Updated successfully";
            $response['student']=$student;
            return $this->sendResponse($response);

        }else{
            $error = "Sorry! Please try again";
            return $this->sendError($error, 401);
        }




    }

    public function paymentCallback(Request $request){
        $rawData = file_get_contents('php://input');
        $request_json = json_decode($rawData,true);
   
        if($request_json['response']){
          $requestData = json_decode(base64_decode($request_json['response']),true);
       
          $orderData = explode('#',$requestData['data']['merchantTransactionId']);
          $transaction=new Transaction();
          $transaction->student_id=$orderData[3];
          $transaction->package_id=$orderData[1];
          $transaction->number_of_months=$orderData[2];
          $transaction->price=$requestData['data']['amount'];
          $transaction->transaction_id=$requestData['data']['transactionId'];
          $transaction->reciept=$requestData['data']['paymentInstrument']['pgTransactionId'];
          $transaction->status=$requestData['data']['state'];
          if($transaction->save()){
            $count=StudentPackage::where('student_id',$transaction->student_id)->count();
            $student_package=new StudentPackage();
            if($count){
                $student_package=StudentPackage::where('student_id',$transaction->student_id)->first();
            }
            $student_package->student_id=$transaction->student_id;
            $student_package->package_id=$transaction->package_id;
            $student_package->number_of_months=$transaction->number_of_months;
            $student_package->price=$transaction->price;
            $student_package->start_date=date('d-m-y');
            $student_package->start_month=date('m');
            $student_package->status=2;
            $student_package->payment_status="compeleted";
            if($count==0?$student_package->save():$student_package->update()){
                $response['message']="Subscription Activated";
                $response['subscription_details']=$student_package;
                return $this->sendResponse($response);
            }else{
                $error = "Sorry! Please try again";
                return $this->sendError($error, 401);
            }
           }else{
              $error = "Sorry! Please try again";
              return $this->sendError($error, 401);
          }
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
        
            $transaction=new Transaction();
            $transaction->student_id=$request->user()->id;
            $transaction->package_id=$request->package_id;
            $transaction->package_name=$request->package_name;
            $transaction->number_of_months=$request->number_of_month;
            $transaction->price=$request->price;
            $transaction->order_id=md5(time());
            $transaction->status='ordered';
            if($transaction->save()){
                $responses['message']="Order Created.";
                $responses['subscription_details']=$transaction;
                return $this->sendResponse($responses);
             }else{
                $error = "Sorry! Please try again";
                return $this->sendError($error, 401);
            }
        
}

public function updatePackage(Request $request){
    $validator = Validator::make($request->all(), [
        'order_id'=>'required',
    ]);
    if($validator->fails()){
        return $this->sendError($validator->errors());
    }

    $transaction=Transaction::where('order_id',$request->order_id)->first();
    $saltKey = env('PHONE_PE_SALT_KEY_PROD');
    $saltIndex = env('PHONE_PE_SALT_INDEX_PROD');
    $merchant_id = env('PHONE_PE_MERCHANTID_PROD');

        $finalXHeader = hash('sha256','/pg/v1/status/'.$merchant_id.'/'.$request->order_id.$saltKey).'###'.$saltIndex;

        $response_encoded = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/status/'.$merchant_id.'/'.$request->order_id)
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:'.$finalXHeader)
                ->withHeader('X-MERCHANT-ID:'.$request->order_id)
                ->get();
        $res = json_decode($response_encoded);
        if($res->success){
                $transaction->status="compeleted";
                $transaction->transaction_id=$res->data->transactionId;
                $transaction->update();
                $count=StudentPackage::where('student_id',$request->user()->id)->count();
                    $student_package=new StudentPackage();
                    if($count){
                        $student_package=StudentPackage::where('student_id',$request->user()->id)->first();
                    }
                    $student_package->student_id=$transaction->student_id;
                    $student_package->package_id=$transaction->package_id;
                    $student_package->package_name=$transaction->package_name;
                    $student_package->number_of_months=$transaction->number_of_months;
                    $student_package->price=$transaction->price;
                    $student_package->start_date=date('d-m-y');
                    $student_package->start_month=date('m');
                    $student_package->status=2;
                    $student_package->payment_status="compeleted";
                    if($count==0?$student_package->save():$student_package->update()){
                        $response['message']="Subscription Activated";
                        $response['subscription_details']=$student_package;
                        return $this->sendResponse($response);
                    }else{
                        $error = "Sorry! Please try again";
                        return $this->sendError($error, 401);
                    }
            
        }else{
            if($res == 'PAYMENT_PENDING'){
                $transaction->status="pending";
                $transaction->update();
            }
            $error = $res->message;
            if($res == 'PAYMENT_ERROR'){
                $error .= ' '.$response->data->responseCodeDescription;
            }
            return $this->sendError($error, 500);
        }
    
}
}
