<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\Faculty;
use App\Models\Branch;

class AuthController extends ResponseController
{

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:students',
            'password' => 'required',
            'phone'=>'required',
            'faculty'=>'required',
            'branch'=>'required',
            'collage'=>'required',
            'district'=>'required',
            'year'=>'required',
            'm_toung'=>'required',
            'instructor'=>'required'

        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = Student::create($input);
        if($user){
            $success['token'] =  $user->createToken('token')->plainTextToken;
            $success['message'] = "Registration successfull..";
            $success['user']=$user;
            return $this->sendResponse($success);
        }
        else{
            $error = "Sorry! Registration is not successfull.";
            return $this->sendError($error, 401);
        }

    }




    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $credentials = request(['email', 'password']);
        if(!Auth::guard('api')->attempt($credentials)){
            $error = "Unauthorized";
            return $this->sendError($error, 401);
        }
        $user =  Auth::guard('api')->user();
        $success['token'] =  $user->createToken('token')->plainTextToken;
        $success['user'] = $user;
        return $this->sendResponse($success);
    }



    //getuser
    public function getUser(Request $request)
    {
        //$id = $request->user()->id;
        $user = $request->user();
        $user->branch=Branch::where('branch_id',$request->user()->branch)->value('name');
        $user->faculty=Faculty::where('faculty_id',$request->user()->branch)->value('name');

        if($user){
            return $this->sendResponse($user);
        }
        else{
            $error = "user not found";
            return $this->sendResponse($error);
        }
    }
}
