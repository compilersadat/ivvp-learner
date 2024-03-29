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
      <div class="card-header"><strong>EDIT COLLAGE</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('collages.update', $data->id)}}" >
                @csrf
                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Name</label>
                   <input type="text" id="name" name="name" placeholder="Title" class="form-control" value="{{$data->name}}">
                </div>
                <div class="form-group">
                    <div class=" pt-2 ">
                        <label class="text-muted">Select District <font class="text-danger">*</font></label>
                        <select class="select w-100 text-16" name="district" id="faculty" required="">
                        <option>Select District</option>
                        @foreach(@App\Models\District::all() as $opt)

                        <option value="{{$opt->id}}" @if($data->district===$opt->id) selected @endif>{{$opt->Name}}</option>

                        @endforeach

                        </select>

                    </div>
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