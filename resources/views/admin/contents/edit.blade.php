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
                    <label for="pNO" class=" form-control-label">Barcode</label>
                   <input type="text" id="barcode" name="barcode" placeholder="Enter code" class="form-control" value="{{$content->barcode}}">
                </div>

                <div class="form-group">
                    <div class=" pt-2 ">
                        <label class="text-muted">Select File <font class="text-danger">*</font></label>
                        <select class="select w-100 text-16" name="file_url" id="file_url" required="">
                        <option>Select File</option>
                        @foreach(@App\Models\S3upload::all() as $opt)

                        <option value="{{$opt->id}}" @if($content->file_url==$opt->id) selected @endif>{{$opt->title}}</option>

                        @endforeach

                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="type" class=" form-control-label">Select Type</label>

                      <select class="form-control" name="type">
                        <option value="">{{ __('Select Type') }}</option>

                        <option value="video_lecture" @if($content->type=="video_lecture") selected @endif>Video Lecture</option>
                        <option value="file_pdf" @if($content->type=="file_pdf") selected @endif>File PDF</option>
                        <option value="free_video" @if($content->type=="free_video") selected @endif>Free Video</option>
                        <option value="free_pdf"  @if($content->type=="free_pdf") selected @endif>Free PDF</option>
                      </select>

                  </div>
                  <img width="350" src="{{env('S3_STORAGE_BASE_URL').$content->thumbnail}}"/>
                  <div class="form-group">
                    <label for="thumbnail" class=" form-control-label">Select Thumnail</label>
                   <input type="file" id="thumbnail" name="thumbnail" class="form-control">
                </div>
                

                <div class="row">
                    <div class="col-md-6">
                        <div class=" pt-2 ">
                            <label class="text-muted">Select Faculty <font class="text-danger">*</font></label>
                            <select class="select w-100 text-16" name="faculty" id="faculty" required="">
                            {{-- <option>Select Faculty</option> --}}
                            @foreach(@App\Models\Faculty::all() as $opt)

                            <option value="{{$opt->faculty_id}}" {{($content->faculty == $opt->faculty_id) ? "selected" : ''}}>{{$opt->name}}</option>


                            @endforeach

                            </select>

                        </div>
                    </div>


                <div class="col-md-6">

                    <div class=" pt-2">
                        <label class="text-muted">Select Branch <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="branch" id="tre" required="">
                        <option>Select Branch</option>
                        @foreach(@App\Models\Branch::all() as $opt)

                        <option value="{{$opt->branch_id}}" {{($content->branch == $opt->branch_id) ? "selected" : ''}}>{{$opt->name}}</option>


                        @endforeach

                        </select>

                    </div>
                </div>

                <div class="col-md-6 mt-2">

                    <div class=" pt-2">
                        <label class="text-muted">Select Year <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="year" id="year" required="">
                        <option>Select Year</option>
                          <option value="1"  @if($content->year==1) selected @endif>1</option>
                          <option value="2"  @if($content->year==2) selected @endif>2</option>
                          <option value="3"  @if($content->year==3) selected @endif>3</option>
                        </select>

                    </div>
                </div>

                <div class="col-md-6 mt-2">

                    <div class=" pt-2">
                        <label class="text-muted">Select Month <font class="text-danger">*</font></label>

                        <select class="select w-100 text-16" name="month" id="month" required="">
                        <option value="">Select Month</option>
                        <option value="1"  @if($content->month==1) selected @endif>January</option>
                        <option value="2"  @if($content->month==2) selected @endif>Febuary</option>
                        <option value="3" @if($content->month==3) selected @endif>March</option>
                        <option value="4" @if($content->month==4) selected @endif>April</option>
                        <option value="5" @if($content->month==5) selected @endif>May</option>
                        <option value="6" @if($content->month==6) selected @endif>June</option>
                        <option value="7" @if($content->month==7) selected @endif>July</option>
                        <option value="8" @if($content->month==8) selected @endif>August</option>
                        <option value="9" @if($content->month==9) selected @endif>September</option>
                        <option value="10" @if($content->month==10) selected @endif>October</option>
                        <option value="11" @if($content->month==11) selected @endif>November</option>
                        <option value="12" @if($content->month==12) selected @endif>December</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="pNO" class=" form-control-label">Order</label>
                       <input type="text" id="order_by" name="order_by" placeholder="order_by" value="{{$content->order_by}}" class="form-control">
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
