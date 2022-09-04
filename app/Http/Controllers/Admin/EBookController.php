<?php

namespace App\Http\Controllers\Admin;

use App\Ebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ebooks=Ebook::all();
        return view('admin.ebooks.index', compact('ebooks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ebooks.create');
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
            'name'=>'required',
            'image'=>'required',
            'price'=>'required',

            
            

        ]);
        $ebook=new Ebook();
        $ebook->name=$request->name;
        $ebook->price=$request->price;
        $ebook->branch_id=$request->branch_id;
        $ebook->faculty_id=$request->faculty_id;
        $ebook->description=$request->description;
        $ebook->book_type=$request->book_type;
        if($request->file('image')) {
            $upload = $request->file('image');
            $fileformat = time() . '.' . $upload->getClientOriginalName();
            if ($upload->move('site/storage/app/ebook/', $fileformat)) {
                $ebook->image = $fileformat;
            }

        }
        if($ebook->save()){
            return redirect()->route('ebook.index')->with('success',' Ebook Added successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ebook=Ebook::where('id', $id)->first();
        return view('admin.ebooks.show', compact('ebook'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ebook=Ebook::where('id', $id)->first();
        return view('admin.ebooks.edit', compact('ebook'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'image'=>'required',
            'price'=>'required',

            
            

        ]);
        $ebook=Ebook::where('id', $id)->first();
        $ebook->name=$request->name;
        $ebook->price=$request->price;
        $ebook->branch_id=$request->branch_id;
        $ebook->faculty_id=$request->faculty_id;
        $ebook->description=$request->description;
        $ebook->book_type=$request->book_type;
        if($request->file('image')) {
            $upload = $request->file('image');
            $fileformat = time() . '.' . $upload->getClientOriginalName();
            if ($upload->move('site/storage/app/ebook/', $fileformat)) {
                $ebook->image = $fileformat;
            }

        }
        if($ebook->update()){
            return redirect()->route('ebook.index')->with('success',' Ebook Update successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $ebook=Ebook::findOrFail($id);
        if($ebook::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Ebook deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }
}
