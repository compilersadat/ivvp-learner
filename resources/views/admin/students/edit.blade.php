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
      <div class="card-header"><strong>EDIT student</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('student.update', $student->id)}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Name</label>
                   <input type="text" id="name" name="name" placeholder="name" class="form-control" value="{{$student->name}}">
                </div>

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">email</label>
                   <input type="text" id="email" name="email" placeholder="email" class="form-control" value="{{$student->email}}">
                </div>

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Phone</label>
                   <input type="text" id="phone" name="phone" placeholder="phone" class="form-control" value="{{$student->phone}}">
                </div>
            
                

                
                    <div class="row">
                        <div class=" col-6 form-group">
                            <div class=" pt-2 ">
                                <label class="text-muted">Select Faculty <font class="text-danger">*</font></label>
                                <select class="select w-100 text-16" name="faculty" id="faculty" required="">
                                {{-- <option>Select Faculty</option> --}}
                                @foreach(@App\Models\Faculty::all() as $opt)
    
                                <option value="{{$opt->faculty_id}}" {{($student->faculty == $opt->faculty_id) ? "selected" : ''}}>{{$opt->name}}</option>
    
    
                                @endforeach
    
                                </select>
    
                            </div>
                        </div>
    
    
                    <div class="col-6 form-group">
    
                        <div class=" pt-2">
                            <label class="text-muted">Select Branch <font class="text-danger">*</font></label>
    
                            <select class="select w-100 text-16" name="branch" id="tre" required="">
                            <option>Select Branch</option>
                            @foreach(@App\Models\Branch::all() as $opt)
    
                            <option value="{{$opt->branch_id}}" {{($student->branch == $opt->branch_id) ? "selected" : ''}}>{{$opt->name}}</option>
    
    
                            @endforeach
    
                            </select>
    
                        </div>
                    </div>

                    </div>
                

                <div class="row">

                    <div class="col-6 form-group">

                        <div class=" pt-2">
                            <label class="text-muted">Select Year <font class="text-danger">*</font></label>
    
                            <select class="select w-100 text-16" name="year" id="year" required="">
                            <option>Select Year</option>
                              <option value="1"  @if($student->year==1) selected @endif>1</option>
                              <option value="2"  @if($student->year==2) selected @endif>2</option>
                              <option value="3"  @if($student->year==3) selected @endif>3</option>
                            </select>
    
                        </div>
                    </div>
    
                    <div class=" col-6 form-group">
                        <div class=" pt-2 ">
                            <label class="text-muted">Select Collage <font class="text-danger">*</font></label>
                            <select class="select w-100 text-16" name="collage" id="collage" required="">
                            <option>Select Collage</option>
                            @foreach(@App\Models\Collage::all() as $optclg)
    
                            <option value="{{$optclg->name}}" @if($student->collage===$optclg->name) selected @endif>{{$optclg->name}}</option>
    
                            @endforeach
    
                            </select>
    
                        </div>
                    </div>

                </div>


                <div class="form-group">
                    <label for="pNO" class=" form-control-label">instructor</label>
                   <input type="text" id="instructor" name="instructor" placeholder="instructor" class="form-control" value="{{$student->instructor}}">
                </div>
                <div class="form-group">
                    <label for="pNO" class=" form-control-label">district</label>
                   <input type="text" id="district" name="district" placeholder="district" class="form-control" value="{{$student->district}}">
                </div>

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">Mother tongue</label>
                   <input type="text" id="m_toung" name="m_toung" placeholder="Mother tongue" class="form-control" value="{{$student->m_toung}}">
                </div>

                <div class="form-group">
                    <label for="pNO" class=" form-control-label">image</label>
                    <img width="350" src="{{$student->image}}"/>
                   <input type="file" id="image" name="image" placeholder="image" class="form-control" value="{{$student->image}}">
                </div>
                
                <div class="form-group">
                    <label for="pNO" class=" form-control-label">paid</label>
                   <input type="text" id="paid" name="paid" placeholder="paid" class="form-control" value="{{$student->paid}}">
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
