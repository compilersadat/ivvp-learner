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
      <div class="card-header"><strong>EDIT SUBSCRIPTION</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('packages.update', $data->id)}}" >
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Title</label>
                   <input type="text" id="name" name="name" placeholder="Title" class="form-control" value="{{$data->name}}">
                </div>
                

                
                
              


     <div class="form-group">
       
         <label for="pNO" class=" form-control-label">Number Of Months</label>
        <input type="number" id="pNo" name="number" placeholder="Page Number" class="form-control" value="{{$data->number}}">
     </div>

     

    
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea id="editor1" class="form-control" name="description" rows="5" cols="40" placeholder="Enter Data">
                {{$data->description}}
            </textarea>
        </div>

        <div class="form-group">
       
            <label for="pNO" class=" form-control-label">Price</label>
           <input type="price" id="pNo" name="price" placeholder="Price" class="form-control" value="{{$data->price}}">
        </div>
   


     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>                                      
     </div>
 </div>
</div>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
@endsection