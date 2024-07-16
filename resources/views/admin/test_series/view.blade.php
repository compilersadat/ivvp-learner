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
                        <thead>
                            <tr>
                                <th>SR NO</th>
                                <th>Name</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;?>
                            @foreach($sections as $section)
                            <tr>
                             <td>{{$i}}</td>
                             <td>{{isset($section->name) ? ($section->name) : '-'}}</td>
                            
                        
                            </tr>
                          <?php $i++?>
                           @endforeach
                        </tbody>
                    </table>
                    <div class="card-header"><strong>Add new Section</strong></div>
                    <form method="POST" action="{{route('testseries.store.section')}}">
                        @csrf
        
                        <div class="form-group">
                            <label for="name" class=" form-control-label">Name</label>
                           <input type="text" id="name" name="name" placeholder="Title" class="form-control">
                        </div>
                        <input type="text" id="test_series_id" name="test_series_id" class="form-control" hidden  value="{{$testseries->id}}">
        
               
        
        
        
             <div class="card">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
                </div>

            </div>

            
        </div>
        
    </div>
    
  </div>



@endsection
