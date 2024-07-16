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
<div class="col-lg-12">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>ADD NEW QUESTION</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{route('test.questions.store')}}" enctype="multipart/form-data">
                @csrf

               <input type="hidden" name="exam" value="{{$test->id}}"/>

        <div class="form-group">
            <label class="form-label">Question</label>
            <textarea id="editor1" class="form-control" name="question" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Option One</label>
            <textarea id="editor2" class="form-control" name="A" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Option Two</label>
            <textarea id="editor3" class="form-control" name="B" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Option Three</label>
            <textarea id="editor4" class="form-control" name="C" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Option Four</label>
            <textarea id="editor5" class="form-control" name="D" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
       
        <div class="form-group">
            <div class=" pt-2">
                <label class="text-muted">Answer <font class="text-danger">*</font></label>

                <select class="select w-100 text-16" name="answer" id="answer" required="">
                <option value="A" selected>A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
             

                </select>

            </div>
        </div>
    </div>



     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
     </div>
 </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script>
  <script src="{{ asset('js/lib/data-table/datatables.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/dataTables.bootstrap.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/dataTables.buttons.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/buttons.bootstrap.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/jszip.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/vfs_fonts.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/buttons.html5.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/buttons.print.min.js')}}"></script>
      <script src="{{ asset('js/lib/data-table/buttons.colVis.min.js')}}"></script>
      <script src="{{ asset('js/init/datatables-init.js')}}"></script>
           <script type="text/javascript">
          $(document).ready(function() {
            $('#bootstrap-data-table-export').DataTable();
        } );
    </script>
  
  <script type="text/javascript">
      CKEDITOR.replace('editor1', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor2', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor3', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor4', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor5', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor6', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
      CKEDITOR.replace('editor7', {
          filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
  
  </script>
 @endsection