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
      <div class="card-header"><strong>EDIT CONTENT</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('content.update', $content->id)}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Title</label>
                   <input type="text" id="title" name="title" placeholder="Title" class="form-control" value="{{$content->title}}">
                </div>

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">File URL</label>
                   <input type="text" id="file_url" name="file_url" placeholder="File URL" class="form-control" value="{{$content->file_url}}">
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


                <div class="row">
                    <div class="col-md-6">
                        <div class=" pt-2 ">
                            <label class="text-muted">Select Faculty <font class="text-danger">*</font></label>
                            <select class="select w-100 text-16" name="faculty" id="faculty" required="">
                            {{-- <option>Select Faculty</option> --}}
                            @foreach(@App\Faculty::all() as $opt)

                            <option value="{{$opt->id}}" {{($content->faculty == $opt->id) ? "selected" : ''}}>{{$opt->name}}</option>


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
                        <option value="january">January</option>
                        <option value="febuary">Febuary</option>
                        <option value="march">March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                        <option value="june">June</option>
                        <option value="july">July</option>
                        <option value="august">August</option>
                        <option value="septempber">September</option>
                        <option value="october">October</option>
                        <option value="november">November</option>
                        <option value="december">December</option>

                        </select>

                    </div>
                </div>

            </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea id="editor1" class="form-control" name="description" rows="5" cols="40" placeholder="Description">
                {{$content->title}}
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
