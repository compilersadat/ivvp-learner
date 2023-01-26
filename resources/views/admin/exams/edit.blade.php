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
      <div class="card-header"><strong>EDIT EXAM</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('exams.update', $content->id)}}">
                @csrf

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Title</label>
                   <input type="text" id="title" name="title" placeholder="Title" class="form-control" value="{{$content->title}}">
                </div>
                

              
                <div class="row">
                    <div class="col-md-6">
                        <div class=" pt-2 ">
                            <label class="text-muted">Select Faculty <font class="text-danger">*</font></label>
                            <select class="select w-100 text-16" name="faculty" id="faculty" required="">
                            <option>Select Faculty</option>
                            @foreach(@App\Models\Faculty::all() as $opt)

                            <option value="{{$opt->faculty_id}}" @if($content->faculty==$opt->faculty_id)>{{$opt->name}}</option>

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
                <div class=" pt-2">
                    <label class="text-muted">Published <font class="text-danger">*</font></label>

                    <select class="select w-100 text-16" name="is_published" id="is_pulished" required="">
                    <option value="0" @if ($content->is_published==0)
                    selected
                    @endif >No</option>
                    <option value="1" @if ($content->is_published==1)
                        selected
                        @endif >Yes</option>
                    </select>

                </div>
            </div>
            <div class="form-group">
                <label for="pNO" class=" form-control-label">Marks</label>
               <input type="number" id="marks" name="marks" placeholder="Title" class="form-control" value="{{$content->marks}}">
            </div>
            <div class="form-group">
                <label for="pNO" class=" form-control-label">Number Of Questions</label>
               <input type="number" id="no_questions" name="no_questions" placeholder="Title" class="form-control" value="{{$content->no_questions}}">
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
