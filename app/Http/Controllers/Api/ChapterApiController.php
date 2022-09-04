<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Chapter;
use Illuminate\Support\Facades\Validator;

class ChapterApiController extends Controller
{
    public function allChapter(Request $request){
        $validator = Validator::make($request->all(), [

            'book_id'=>'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'F',
                'message'=>$validator->errors()->first()
                ]);
        }

        $chapter=Chapter::where('book_id', $request->book_id)->get();
        return response()->json([
            'status'=>'S',
            'chapter'=>$chapter,
          ]);
    }
}
