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
use App\Services\PhonePeClient;             // <-- add
use Illuminate\Support\Facades\Log;         // <-- optional logging
use Illuminate\Support\Str;   

class UserController extends ResponseController
{
    public function __construct(private PhonePeClient $phonePe) {}  // <-- inject

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
    
    public function subscribPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id'       => 'required|integer',
            'package_name'     => 'required|string|max:255',
            'number_of_month'  => 'required|integer|min:1',
            'price'            => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
    
        // 1) Create our local order in DB
        $transaction             = new Transaction();
        $transaction->student_id = $request->user()->id;
        $transaction->package_id = $request->package_id;
        $transaction->package_name = $request->package_name;
        $transaction->number_of_months = (int) $request->number_of_month;
        $transaction->price = (float) $request->price;
    
        // Use a PhonePe-safe merchantOrderId: [A-Za-z0-9_-], <= 63 chars.
        // md5(time()) is ok; here's a slightly clearer ID:
        $merchantOrderId = 'ORD_' . substr(md5(uniqid((string) now()->timestamp, true)), 0, 28); // e.g., ORD_ + 32hex truncated
        $transaction->order_id = $merchantOrderId;
    
        $transaction->status = 'ordered';
    
        if (!$transaction->save()) {
            return $this->sendError('Sorry! Please try again', 401);
        }
    
        try {
            // 2) Ask PhonePe for an SDK order token
            // amount is in paise
            $amountPaise = (int) round($transaction->price * 100);
    
            $payload = [
                'merchantOrderId' => $merchantOrderId,
                'amount'          => $amountPaise,
                'paymentFlow'     => ['type' => 'PG_CHECKOUT'],
                // optional, but useful:
                'metaInfo'        => [
                    'udf1' => (string) $transaction->student_id,
                    'udf2' => (string) $transaction->package_id,
                    'udf3' => (string) $transaction->number_of_months,
                    'udf4' => (string) $transaction->package_name,
                ],
                // 'expireAfter'   => 900, // optional: 300..3600 seconds
            ];
    
            $pp = $this->phonePe->createOrderToken($payload);
    
            // PhonePe returns { success, code, data: { orderId, token, ... } }
            $ppData  = $pp['data'] ?? [];
            $orderId = $ppData['orderId'] ?? null;
            $token   = $ppData['token']   ?? null;
    
            if (!$orderId || !$token) {
                Log::warning('PhonePe order token missing', ['resp' => $pp]);
                return $this->sendError('Could not create payment order. Please try again.', 500);
            }
    
            // (Optional) persist PG details on the transaction if you have columns
            // $transaction->pg_order_id = $orderId;
            // $transaction->pg_token    = $token;
            // $transaction->save();
    
            // 3) Return both your order and the PhonePe token to the app
            // Your Flutter code expects {data: {orderId, token}} or flat fields; so include both.
            $response = [
                'message'              => 'Order Created.',
                'subscription_details' => $transaction,
                'phonepe'              => [
                    'merchantOrderId' => $merchantOrderId,  // send back for your reference
                    'orderId'         => $orderId,
                    'token'           => $token,
                ],
            ];
            return $this->sendResponse($response);
    
        } catch (\Throwable $e) {
            Log::error('PhonePe createOrderToken failed', ['ex' => $e]);
            return $this->sendError('Payment initialization failed. Please try again.', 500);
        }
    }
    

    public function updatePackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
    
        // 1) Fetch local order
        $transaction = Transaction::where('order_id', $request->order_id)->first();
        if (!$transaction) {
            return $this->sendError('Order not found', 404);
        }
    
        try {
            // 2) NEW: Check status via PhonePe OAuth client
            // details/errorContext are optional; set true for richer data
            $pp = $this->phonePe->getOrderStatus($request->order_id, details: true, errorContext: true);
    
            $success = (bool)($pp['success'] ?? false);
            $data    = $pp['data'] ?? [];
            $state   = strtoupper((string)($data['state'] ?? '')); // COMPLETED | FAILED | PENDING
    
            // try a few places for transaction ids (varies by instrument)
            $pgTxnId = $data['paymentInstrument']['pgTransactionId'] ?? null;
            $txnId   = $data['transactionId'] ?? $pgTxnId;
    
            if ($success && $state === 'COMPLETED') {
                // 3a) Mark transaction as completed (note: your code uses "compeleted")
                $transaction->status = 'compeleted'; // keep existing spelling to avoid breaking other logic
                if ($txnId) {
                    $transaction->transaction_id = $txnId;
                }
                $transaction->save();
    
                // 4) Upsert student package
                $studentId = $request->user()->id;
                $studentPackage = StudentPackage::firstOrNew(['student_id' => $studentId]);
    
                $studentPackage->student_id        = $transaction->student_id;
                $studentPackage->package_id        = $transaction->package_id;
                $studentPackage->package_name      = $transaction->package_name;
                $studentPackage->number_of_months  = $transaction->number_of_months;
                $studentPackage->price             = $transaction->price;
                $studentPackage->start_date        = date('d-m-y');
                $studentPackage->start_month       = date('m');
                $studentPackage->status            = 2;
                $studentPackage->payment_status    = 'compeleted'; // keep existing spelling
    
                $studentPackage->save();
    
                $response = [
                    'message'               => 'Subscription Activated',
                    'subscription_details'  => $studentPackage,
                ];
                return $this->sendResponse($response);
            }
    
            // 3b) Non-completed paths
            if ($state === 'PENDING') {
                $transaction->status = 'pending';
                $transaction->save();
                return $this->sendError('Payment is pending', 202);
            }
    
            // FAILED or unexpected
            $transaction->status = 'failed';
            $transaction->save();
    
            // Include PhonePe error context if available
            $message = $pp['message'] ?? 'Payment failed';
            if (!empty($pp['errorContext'])) {
                $message .= ' - ' . json_encode($pp['errorContext']);
            }
            return $this->sendError($message, 500);
    
        } catch (\Throwable $e) {
            // 5) Hard failure talking to PhonePe
            \Log::error('PhonePe order status check failed', [
                'order_id' => $request->order_id,
                'ex'       => $e->getMessage(),
            ]);
            return $this->sendError('Unable to verify payment at the moment. Please try again.', 500);
        }
    }
    
}
