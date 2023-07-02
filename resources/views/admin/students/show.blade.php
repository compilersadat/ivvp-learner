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
      <div class="card-header"><strong>Student Details</strong></div>
        <div class="card-body card-block">
            <div class="card" >
                <div class="card-header">
                   <b> Personal details</b>
                </div>
                
                    <img src="{{$student->name}}" class="card-img-top" style="width: 18rem;" alt="img">
                  
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Name
                        </div>
                        <div class="col">
                            {{$student->name}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Email
                        </div>
                        <div class="col">
                            {{$student->email}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Phone Number
                        </div>
                        <div class="col">
                            {{$student->phone}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Mother tongue
                        </div>
                        <div class="col">
                            {{$student->m_toung}}
                        </div>
                    </div>
                  </li>
                </ul>
              </div>


              <div class="card" >
                <div class="card-header">
                   <b> Educational details</b>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Collage
                        </div>
                        <div class="col">
                            {{$student->collage}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Branch
                        </div>
                        <div class="col">
                            {{$student->branch}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            faculty
                        </div>
                        <div class="col">
                            {{$student->faculty}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Instructor
                        </div>
                        <div class="col">
                            {{$student->instructor}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Year
                        </div>
                        <div class="col">
                            {{$student->year}}
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            Paid
                        </div>
                        <div class="col">
                            @if($student->paid == 0)
                                <span class="badge badge-danger">Free</span>
                            @elseif($student->paid == 1)
                                <span class="badge badge-success">Premium</span>
                            @endif
                        </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            District
                        </div>
                        <div class="col">
                            {{$student->district}}
                        </div>
                    </div>
                  </li>
                </ul>
              </div>
     
              <div class="card" >
                <div class="card-header">
                  
                  <b>Account status</b>
                </div>

                <div class="card-body">
                    <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" checked>
                    <label class="btn btn-outline-success" for="success-outlined">Activate</label>
                    <br>
                    <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off">
                    <label class="btn btn-outline-danger" for="danger-outlined">Deactivate</label>
                  </div>
              </div>
            </div>

    
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