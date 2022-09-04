<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ebook;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    public function allBook(Request $request){
        $validator = Validator::make($request->all(), [

            'branch_id'=>'required',
            


        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'F',
                'message'=>$validator->errors()->first()
                ]);
        }

        $book=Ebook::where('branch_id', $request->branch_id)->get();
        return response()->json([
            'status'=>'S',
            'book'=>$book,
          ]);
    }
}
