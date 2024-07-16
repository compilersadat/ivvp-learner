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
<div class="col-md-12">
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
                    <td>{!! $question->A !!}</td>
                    <td>{!! $question->B !!}</td>
                    <td>{!! $question->C !!}</td>
                    <td>{!! $question->D !!}</td>
                    <td>{!! $question->answer !!}</td>
                    
                    <td>
                        <a href="{{route('practice.questions.edit',$question->id)}}" class="label  "><i class="fa fa-edit fa-1x" style="color: #000"></i> </a>
                                          <a href="{{route('practice.questions.delete',$question->id)}}" onclick="return confirm('Are you sure you want to delete this item?');" class="label bg-red-active"><i class="fa fa-trash  fa-1x" style="color: #000"></i> </a>
                                          
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
   @section