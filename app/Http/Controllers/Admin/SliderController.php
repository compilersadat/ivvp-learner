<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders=Slider::get();
        return view('admin.sliders.index', compact('sliders'));
    }
    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'content' => 'required',
        ]);

                $path = Storage::disk('s3')->put('images', $request->content);



        // $path = Storage::url($path);
        /* Store $imageName name in DATABASE from HERE */

        $content = Slider::create([

             'image'=>$path,
        ]);

        session()->flash('status', 'Content Uploaded Successfully');
        return redirect()->route('slider.index');
}
}
