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
                        <strong>Tests for {{$testseries->name}} | {{$tests->count()}} Tests </strong>
                        <a href="{{route('testseries.changeStatus',$testseries->id)}}" class=" btn btn-sm btn-primary"><i class="fa fa-cloud-upload"></i> @if ($testseries->is_published==0)
                            Publish
                        @else
                            Deactive
                        @endif</a>
                    </div>
                </div>
                <div class="card-body card-block">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                      
                    </table>
                </div>

            </div>

            
        </div>
        
    </div>
    
  </div>



@endsection
