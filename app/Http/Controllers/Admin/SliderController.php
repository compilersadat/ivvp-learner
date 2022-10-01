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

        $content = Slider::create([

             'image'=>$path,
        ]);

        session()->flash('status', 'Content Uploaded Successfully');
        return redirect()->route('slider.index');
}

    public function delete($id){
         $slider=Slider::where('id',$id)->first();

         if(Storage::disk('s3')->exists($slider->image)) {
            Storage::disk('s3')->delete($slider->image);
            if(Slider::where('id',$id)->delete()){
                session()->flash('status', 'Slider Deleted Successfully');
                return redirect()->route('slider.index');
            }else{
                session()->flash('status', 'Error in Deleting Slider');
                return redirect()->route('slider.index');
            }
        }else{
            if(Slider::where('id',$id)->delete()){
                session()->flash('status', 'Slider Deleted Successfully');
                return redirect()->route('slider.index');
            }else{
                session()->flash('status', 'Error in Deleting Slider');
                return redirect()->route('slider.index');
            }
        }
    }
}
