<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Data;
use Illuminate\Support\Facades\Validator;

class DataApiController extends Controller
{
    public function allData(Request $request){
        $validator = Validator::make($request->all(), [

            'chapter_id'=>'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'F',
                'message'=>$validator->errors()->first()
                ]);
        }

        $data=Data::where('chapter_id', $request->chapter_id)->get();
        return response()->json([
            'status'=>'S',
            'data'=>$data,
          ]);
    }
}
