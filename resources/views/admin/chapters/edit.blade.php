@extends('layouts.layout')
@section('content')
<div class="col-lg-6">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>EDIT Chapter</strong></div>
         <div class="card-body card-block">
            <form method="POST" action="{{route('chapter.update', $chapter->id)}}" enctype="multipart/form-data">
                @csrf
                 @method('PATCH')
                
                

                <div class="form-group">
                  <label for="faculty" class=" form-control-label">Select Book</label>
                  
                    <select class="form-control" name="book_id">
                      <option value="">{{ __('Select Book') }}</option>
                      @foreach(@App\Ebook::all() as $row)
                      <option value="{{$row->id}}" {{ ($chapter->book_id == $row->id) ? "selected" : ''}}>{{$row->name}}</option>
                      @endforeach
                    </select>
                  
                </div>


                <div class="form-group">
                    <label for="chname" class=" form-control-label">Chapter Name</label>
                   <input type="text" id="chname" name="chapter_name" placeholder="Chapter name" class="form-control" value="{{$chapter->chapter_name}}">
                </div>
           


    
     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

                                            
     </div>
 </div>
</div>


@endsection