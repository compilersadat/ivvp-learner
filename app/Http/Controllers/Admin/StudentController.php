<?php

namespace App\Http\Controllers\admin;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $students = Student::all();
        return view('admin.students.index', compact('students'));


        // $students=Student::all();
        // // $students=Student::get();
        // dd($students);
        // return view('admin.students.index', compact('students'));
        // return view('admin.uploads.index');
        // return view('admin.students.index');
    }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create()
//     {
//         return view('admin.students.create');
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request)
//     {
//         $this->validate($request,[
//             'title'=> 'required',
//             'description' => 'required',
//             'file_url' => 'required',
//             'type' => 'required',
//             'branch' => 'required',
//             'faculty' => 'required',
//             'month' => 'required',
//             'year' => 'required',
//         ]);
//         $path='';
//         if(isset($request->thumbnail)){
//             $path = Storage::disk('s3')->put('thumbnail', $request->thumbnail);
//         }

//         $Student = Student::create([
//             'title' => isset($request->title) ? ($request->title) : '',
//             'description' => isset($request->description) ? ($request->description) : '',
//             'file_url' => isset($request->file_url) ? ($request->file_url) : '',
//             'type' => isset($request->type) ? ($request->type) : '',
//             'branch' => isset($request->branch) ? ($request->branch) : '',
//             'faculty' => isset($request->faculty) ? ($request->faculty) : '',
//             'month' => isset($request->month) ? ($request->month) : '',
//             'year' => isset($request->year) ? ($request->year) : '',
//             'status' => 1,
//             'barcode'=>isset($request->barcode) ? ($request->barcode) : '',
//             'thumbnail'=>$path

//         ]);
//         session()->flash('status', 'Student Create Successfully');
//         return redirect()->route('Student.index');
//     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student=Student::where('id', $id)->first();
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student=Student::where('id', $id)->first();
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=> 'required',
            'email' => 'required',
            'phone' => 'required',
            'collage' => 'required',
            'branch' => 'required',
            'faculty' => 'required',
            'district' => 'required',
            'year' => 'required',
            'paid' => 'required',
        ]);
        $path='';
        $cont=Student::where('id', $id)->first();
        if($request->thumbnail){
            if(isset($request->thumbnail)){
                $path = Storage::disk('s3')->put('thumbnail', $request->thumbnail);
            }  
        }
        $Student = [
            'name' => isset($request->name) ? ($request->name) : '',
            'email' => isset($request->email) ? ($request->email) : '',
            'phone' => isset($request->phone) ? ($request->phone) : '',
            'collage' => isset($request->collage) ? ($request->collage) : '',
            'branch' => isset($request->branch) ? ($request->branch) : '',
            'faculty' => isset($request->faculty) ? ($request->faculty) : '',
            'district' => isset($request->district) ? ($request->district) : '',
            'year' => isset($request->year) ? ($request->year) : '',
            'instructor' => isset($request->instructor) ? ($request->instructor) : '',
            'm_toung' => isset($request->m_toung) ? ($request->m_toung) : '',
            'image' => isset($request->image) ? ($request->image) : '',
            'paid' => isset($request->paid) ? ($request->paid) : '',

        ];

        Student::where('id', $id)->first()->update($Student);
        session()->flash('status', 'Student Update Successfully');
        return redirect()->route('student.index');
    }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function delete($id){

//            if(Student::where('id',$id)->delete()){
//                session()->flash('status', 'Student Deleted Successfully');
//                return redirect()->route('Student.index');
//            }else{
//                session()->flash('status', 'Student in Deleting Slider');
//                return redirect()->route('Student.index');
//            }

//    }
}
