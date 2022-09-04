<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chapters=Chapter::all();
        return view('admin.chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.chapters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'chapter_name'=>'required',
            

            
            

        ]);
        $chapter=new Chapter();
        $chapter->chapter_name=$request->chapter_name;
        $chapter->book_id=$request->book_id;
        if($chapter->save()){
            return redirect()->route('chapter.index')->with('success',' Chapter Added successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chapter=Chapter::where('id', $id)->first();
        return view('admin.chapters.edit', compact('chapter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'chapter_name'=>'required',
            

            
            

        ]);
        $chapter=Chapter::where('id', $id)->first();
        $chapter->chapter_name=$request->chapter_name;
        $chapter->book_id=$request->book_id;
        if($chapter->update()){
            return redirect()->route('chapter.index')->with('success',' Chapter Update successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $chapter=Chapter::findOrFail($id);
        if($chapter::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Chapter deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }


    public function chapterList($id){
        $chapter_list=Data::where('chapter_id', $id)->get();
        return view('admin.ebooks.chapter_data', compact('chapter_list'));
    }
}
