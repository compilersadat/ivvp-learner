<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Student;
use App\Models\TestSeriesUser;
use Illuminate\Support\Facades\Validator;
use App\Models\Faculty;
use App\Models\Branch;
use App\Models\PersonalAccessToken;

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
            $errors = $validator->errors()->toArray();
            $error = '';
            foreach ($errors as $key=>$e){
                foreach($errors[$key] as $se){
                    $error = $error.' '.$se;
                }
            }
            return $this->sendError($error,422);
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



    public function signupTestSeriesUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:test_series_users',
            'password' => 'required',
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->toArray();
            $error = '';
            foreach ($errors as $key=>$e){
                foreach($errors[$key] as $se){
                    $error = $error.' '.$se;
                }
            }
            return $this->sendError($error,422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = TestSeriesUser::create($input);
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
            'email' => 'required|string|email|exists:students',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->toArray();
            $error = '';
            foreach ($errors as $key=>$e){
                foreach($errors[$key] as $se){
                    $error = $error.' '.$se;
                }
            }
            return $this->sendError($error,422);
        }

        $credentials = request(['email', 'password']);
        if(!Auth::guard('api')->attempt($credentials)){
            $error = "Wrong email or password";
            return $this->sendError($error, 401);
        }
        $user =  Auth::guard('api')->user();
        $token=PersonalAccessToken::where('tokenable_id',$user->id)->get();
        if($token->count()>0){
            $error = "User Already Logged in on another device.";
            return $this->sendError($error, 401);
        }
        $success['token'] =  $user->createToken('token')->plainTextToken;
        $success['user'] = $user;
        return $this->sendResponse($success);
    }

    //login
    public function loginTestSeriesUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:test_series_users',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->toArray();
            $error = '';
            foreach ($errors as $key=>$e){
                foreach($errors[$key] as $se){
                    $error = $error.' '.$se;
                }
            }
            return $this->sendError($error,422);
        }

        $credentials = request(['email', 'password']);
        if(!Auth::guard('testseriesapi')->attempt($credentials)){
            $error = "Wrong email or password";
            return $this->sendError($error, 401);
        }
        $user =  Auth::guard('testseriesapi')->user();
        $success['token'] =  $user->createToken('token')->plainTextToken;
        $success['user'] = $user;
        return $this->sendResponse($success);
    }


    //getuser
    public function getUser(Request $request)
    {
        //$id = $request->user()->id;
        $user = $request->user();
        $user['branch']=Branch::where('branch_id',$request->user()->branch)->value('name');
        $user->faculty=Faculty::where('faculty_id',$request->user()->faculty)->value('name');

        if($user){
            return $this->sendResponse($user);
        }
        else{
            $error = "user not found";
            return $this->sendResponse($error);
        }
    }

    public function logout(Request $request){
        $token=PersonalAccessToken::where('tokenable_id',$request->user()->id)->delete();
        $success['message']="Logged Out";
        return $this->sendResponse($success);
    }

    public function delete(Request $request){
        Student::where('id',$request->user()->id)->delete();
        $token=PersonalAccessToken::where('tokenable_id',$request->user()->id)->delete();
        $success['message']="Account Deleted.";
        return $this->sendResponse($success);
    }
}
