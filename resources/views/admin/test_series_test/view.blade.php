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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <strong>Tests for {{$test->title}} | {{$questions->count()}} Questions | {{$questions->sum('marks')}}</strong>
                        <a href="{{route('testseries.test.changeStatus',$test->id)}}" class=" btn btn-sm btn-primary"><i class="fa fa-cloud-upload"></i> @if ($test->is_published==0)
                            Publish
                        @else
                            Deactive
                        @endif</a>
                    </div>
                </div>
                <div class="card-body card-block">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SR NO</th>
                                <th>Question</th>
                                <th>Option A</th>
                                <th>Option B</th>
            
                                <th>Option C</th>
                                <th>Option D</th>
            
                                <th>Answer</th>
                                <th>Marks</th>
                                <th>Negative Marks</th>
            
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $key=>$question)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{!! $question->question !!}</td>
                                <td>{!! $question->option1 !!}</td>
                                <td>{!! $question->option2 !!}</td>
                                <td>{!! $question->option3 !!}</td>
                                <td>{!! $question->option4 !!}</td>
                                <td>{!! $question->answer !!}</td>
                                <td>{{$question->marks}}</td>
                                <td>{{$question->negative_marks}}</td>
                                <td>
                                    <a href="{{route('test.questions.edit',$question->id)}}" class="label  "><i class="fa fa-edit fa-1x" style="color: #000"></i> </a>
                                                      <a href="{{route('test.questions.delete',$question->id)}}" onclick="return confirm('Are you sure you want to delete this item?');" class="label bg-red-active"><i class="fa fa-trash  fa-1x" style="color: #000"></i> </a>
                                                      
                                </td>
            
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                </div>

            </div>

            
        </div>
        <div class="col-lg-12">
            <div class="card">
                @include('partials.alerts')
              <div class="card-header"><strong>ADD NEW QUESTION</strong></div>
                <div class="card-body card-block">
                    <form method="POST" action="{{route('questions.store')}}" enctype="multipart/form-data">
                        @csrf
        
                       <input type="hidden" name="exam" value="{{$test->id}}"/>
        
                <div class="form-group">
                    <label class="form-label">Question</label>
                    <textarea id="editor1" class="form-control" name="question" rows="5" cols="40" placeholder="Description">
                    </textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Option One</label>
                    <textarea id="editor2" class="form-control" name="option1" rows="5" cols="40" placeholder="Description">
                    </textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Option Two</label>
                    <textarea id="editor3" class="form-control" name="option2" rows="5" cols="40" placeholder="Description">
                    </textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Option Three</label>
                    <textarea id="editor4" class="form-control" name="option3" rows="5" cols="40" placeholder="Description">
                    </textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Option Four</label>
                    <textarea id="editor5" class="form-control" name="option4" rows="5" cols="40" placeholder="Description">
                    </textarea>
                </div>
                <div class="form-group">
                    <div class=" pt-2">
                        <label class="text-muted">Answer <font class="text-danger">*</font></label>
        
                        <select class="select w-100 text-16" name="answer" id="answer" required="">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
        
                        </select>
        
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Marks</label>
                    <input  class="form-control" name="marks" placeholder="Marks"/>
                    
                </div>
                <div class="form-group">
                    <label class="form-label">Negative Marks</label>
                    <input  class="form-control" name="negative_marks" placeholder="Negative Marks"/>
                    
                </div>
            </div>
        
        
        
             <div class="card">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
             </div>
         </div>
        
    </div>
    
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
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
  
  
  </script>

@endsection
