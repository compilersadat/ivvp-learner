@extends('layouts.layout')
@section('content')
<div class="row">
   <div class="col">
      @if ($errors->any())
                     <div class="alert alert-danger">
                         <ul>
                             @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                             @endforeach
                         </ul>
                     </div>
                 @endif
                 @if (session('success'))
                     <div class="alert alert-success">
                         {{ session('success') }}
                     </div>
                 @endif
   </div>
   
 </div>

  <div class="col-lg-6">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>ADD NEW CHAPTER</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('chapter.store')}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label for="book" class=" form-control-label">Select Book</label>
                 
                    <select class="form-control" name="book_id">
                      <option value="">{{ __('Select Book') }}</option>
                      @foreach(@App\Ebook::all() as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                  
                </div>



     <div class="form-group">
         <label for="chname" class=" form-control-label">Chapter Name</label>
        <input type="text" id="chname" name="chapter_name" placeholder="Chapter name" class="form-control">
     </div>

     


     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>                                      
     </div>
 </div>
</div>
@endsection