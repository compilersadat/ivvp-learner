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
      <div class="card-header"><strong>ADD NEW Files</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('upload.store')}}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title" class=" form-control-label">Title</label>
                   <input type="text" id="title" name="title" placeholder="Title" class="form-control">
                </div>

                <div class="form-group">
                    <label for="type" class=" form-control-label">Select Type</label>

                      <select class="form-control" name="type">
                        <option value="">{{ __('Select Type') }}</option>

                        <option value="video">Video</option>
                        <option value="pdf">PDF</option>

                      </select>

                  </div>





                  <div class="form-group">
                    <label for="link" class=" form-control-label">File Link</label>
                   <input type="text" id="link" name="link" placeholder="File Link" class="form-control">
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>

            </div>




    </form>
     </div>
 </div>
</div>


@endsection
