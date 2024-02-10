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
      <div class="card-header"><strong>Edit Test</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('testseries.test.update', $test->id)}}">
                @csrf

                <div class="form-group">
                    <label for="title" class=" form-control-label">Title</label>
                   <input type="text" id="title" name="title" placeholder="Title" class="form-control" value="{{$test->title}}">
                </div>
                <div class="col-md-12">
                    <div class=" pt-2 ">
                        <label class="text-muted">Select Test Series <font class="text-danger">*</font></label>
                        <select class="select w-100 text-16" name="test_series_id" id="test_series_id" required="">
                        @foreach(@App\Models\TestSeries::all() as $opt)

                        <option value="{{$opt->id}}" @if($test->testseries_id==$opt->testseries_id) selected @endif>{{$opt->title}}</option>

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



@endsection
