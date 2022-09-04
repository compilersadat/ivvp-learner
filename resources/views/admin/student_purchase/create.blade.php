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

  <div class="col-lg-6">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>ADD NEW STUDENT PURCHASE</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('student-purchase.store')}}" enctype="multipart/form-data">
                @csrf


     <div class="form-group">
       
         <label for="date" class=" form-control-label">Date</label>
        <input type="date" id="date" name="date" placeholder="Date" class="form-control">
     </div>



     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>                                      
     </div>
 </div>
</div>
@endsection