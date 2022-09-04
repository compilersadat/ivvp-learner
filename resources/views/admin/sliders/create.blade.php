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
      <div class="card-header"><strong>ADD NEW SLIDER</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('slider.store')}}" enctype="multipart/form-data">
                @csrf


                <div class="form-group">
                    <input type="file" name="content" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>




    </form>
     </div>
 </div>
</div>


@endsection
