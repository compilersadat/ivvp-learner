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

  <div class="col-lg-12">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>ADD NEW BOOK</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('ebook.store')}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label for="faculty" class=" form-control-label">Select Book Type</label>
                    <select class="form-control" name="book_type">
                      <option value="">{{ __('Select Book Type') }}</option>
                      <option value="theory">Theory</option>
                      <option value="objective">Objective</option>
                    
                    </select>
                </div>

                <div class="form-group">
                  <label for="faculty" class=" form-control-label">Select Faculty</label>
                    <select class="form-control" name="faculty_id">
                      <option value="">{{ __('Select Faculty') }}</option>
                      @foreach(@App\Faculty::all() as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                </div>


                <div class="form-group">
                  <label for="branch" class=" form-control-label">Select Branch</label>
                    <select class="form-control" name="branch_id">
                      <option value="">{{ __('Select Branch') }}</option>
                      @foreach(@App\Branch::all() as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                </div>

                

     <div class="form-group">
       
         <label for="ebook" class=" form-control-label">Book Name</label>
        <input type="text" id="ebook" name="name" placeholder="Book name" class="form-control">
     </div>

     

     <div class="form-group">
        <label for="vat" class=" form-control-label">Price</label>
        <input type="text" id="price" name="price" placeholder="Price" class="form-control">
     </div>

     <div class="form-group">
        <label for="vat" class=" form-control-label">Image</label>
        <input type="file" id="image" name="image" placeholder="Image" class="form-control">
     </div> 

     <div class="form-group">
        <label class="form-label">Description</label>
        <textarea id="editor1" class="form-control" rows="5" cols="40" name="description"
                  placeholder="Description">
        </textarea>
      </div>


     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>                                      
     </div>
 </div>
</div>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace( 'editor1' );
</script>

@endsection