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
            <label class="form-label">Option Five</label>
            <textarea id="editor6" class="form-control" name="option5" rows="5" cols="40" placeholder="Description">
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
                <option value="5">5</option>

                </select>

            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Solution</label>
            <textarea id="editor7" class="form-control" name="solution" rows="5" cols="40" placeholder="Description">
            </textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Marks</label>
            <input  class="form-control" name="marks" placeholder="Marks"/>
            
        </div>
        <div class="form-group">
            <label class="form-label">Negative Marks</label>
            <input  class="form-control" name="negative_marks" placeholder="Negative Marks"/>
            
        </div>

        <div class="col-md-12">
            <div class=" pt-2 ">
                <label class="text-muted">Select section <font class="text-danger">*</font></label>
                <select class="select w-100 text-16" name="section" id="section" required="">
                @foreach($sections as $opt)
                <option value="">Select Section</option>
                <option value="{{$opt->id}}">{{$opt->name}}</option>

                @endforeach

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

 @endsection