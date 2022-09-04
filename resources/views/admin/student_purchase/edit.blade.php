@extends('layouts.layout')
@section('content')
<div class="col-lg-6">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>EDIT STUDENT PURCHASE</strong></div>
         <div class="card-body card-block">
            <form method="POST" action="{{route('student-purchase.update', $student->id)}}" enctype="multipart/form-data">
                @csrf
                 @method('PATCH')
                
               


                <div class="form-group">
       
                    <label for="date" class=" form-control-label">Date</label>
                   <input type="date" id="date" name="date" placeholder="Date" class="form-control" value="{{$student->date}}">
                </div>
        
     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

                                            
     </div>
 </div>
</div>


@endsection