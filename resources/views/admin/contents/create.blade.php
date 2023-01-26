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
      <div class="card-header"><strong>ADD NEW CONTENT</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('content.store')}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Title</label>
                   <input type="text" id="title" name="title" placeholder="Title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Barcode</label>
                   <input type="text" id="barcode" name="barcode" placeholder="Enter code" class="form-control">
                </div>
                <div class="form-group">
                    <div class=" pt-2 ">
                        <label class="text-muted">Select File <font class="text-danger">*</font></label>
                        <select class="select w-100 text-16" name="file_url" id="file_url" required="">
                        <option>Select File</option>
                        @foreach(@App\Models\S3upload::all() as $opt)

                        <option value="{{$opt->id}}">{{$opt->title}}</option>

                        @endforeach

                        </select>

                    </div>
                </div>


                <div class="form-group">
                    <label for="type" class=" form-control-label">Select Type</label>

                      <select class="form-control" name="type">
                        <option value="">{{ __('Select Type') }}</option>

                        <option value="video_lecture">Video Lecture</option>
                        <option value="file_pdf">File PDF</option>
                        <option value="free_video">Free Video</option>
                        <option value="free_pdf">Free PDF</option>
                      </select>

                  </div>

                  <div class="form-group">
                    <label for="thumbnail" class=" form-control-label">Select Thumnail</label>
                   <input type="file" id="thumbnail" name="thumbnail" class="form-control">
                </div>
                


                <div class="row">
                    <div class="col-md-6">
                        <div class=" pt-2 ">
                            <label class="text-muted">Select Faculty <font class="text-danger">*</font></label>
                            <select class="select w-100 text-16" name="faculty" id="faculty" required="">
                            <option>Select Faculty</option>
                            @foreach(@App\Models\Faculty::all() as $opt)

                            <option value="{{$opt->faculty_id}}">{{$opt->name}}</option>

                            @endforeach

                            </select>

                        </div>
                    </div>


                <div class="col-md-6">

                    <div class=" pt-2">
                        <label class="text-muted">Select Branch <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="branch" id="tre" required="">
                        <option>Select Branch</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-6 mt-2">

                    <div class=" pt-2">
                        <label class="text-muted">Select Year <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="year" id="year" required="">
                        <option>Select Year</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-6 mt-2">

                    <div class=" pt-2">
                        <label class="text-muted">Select Month <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="month" id="month" required="">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">Febuary</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>

                        </select>

                    </div>
                </div>

            </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea id="editor1" class="form-control" name="description" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>




     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
     </div>
 </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });


  $('#faculty').on('change',function(e){
        console.log(e);
        var fac = e.target.value;
        let url = '{{route('ajax-get-trends')}}'+'?fac='+fac;

        $.get(url, function(data){
            $('#tre').empty();
            $('#tre').append('<option value="">--Select Branch-</option>');
            $.each(data, function(index, obj){
                $('#tre').append('<option value="'+obj.branch_id+'">'+obj.name+'</option>');
            })
        });

        let year_url = '{{route('ajax-get-years')}}'+'?fac='+fac;
        $.get(year_url, function(data){
        $('#year').empty();
        $('#year').append('<option value="">--Select Year-</option>');
            for (var i = 1; i <= data.duration; i++) {
                $('#year').append('<option value="'+i+'">'+i+'</option>');
                }
        });


    });
</script>


@endsection
