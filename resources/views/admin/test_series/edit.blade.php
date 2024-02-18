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
      <div class="card-header"><strong>Edit test series</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('testseries.update', $test->id)}}">
                @csrf
                <div class="form-group">
                    <label for="name" class=" form-control-label">Name</label>
                   <input type="text" id="name" name="name" placeholder="Name" class="form-control" value="{{$test->name}}">
                </div>
                <div class="form-group">
                    <label for="price" class=" form-control-label">Price</label>
                    <input type="text" id="price" name="price" placeholder="Price" class="form-control" value="{{$test->price}}">
                 </div>
     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
     </div>
 </div>
</div>



@endsection
