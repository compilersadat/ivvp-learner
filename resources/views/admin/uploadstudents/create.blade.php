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
            <a href="{{ route('uploadstudent.download') }}" class="btn btn-primary">Download File</a><br> <br>

            <form method="POST" action="{{route('uploadstudent.store')}}" enctype="multipart/form-data">
                @csrf

                <input type="file" name="file"
                           class="form-control">
                           @if ($errors->has('file'))
                            <span class="text-danger">{{ $errors->first('file') }}</span>
                        @endif
                    <br>
                    


                <button type="submit" class="btn btn-primary">Import User Data</button>

            </div>




    </form>
     </div>
 </div>
</div>


@endsection
