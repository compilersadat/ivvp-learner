@extends('layouts.layout')
@section('content')

<div class="col-md-6">
    <h3 class="card-title">Chapter Details:</h3>
</div>
<div class="list-group">
    
    @foreach($chapter_list as $row)
   
    <div class="list-group-item">
        <div class="row">
            <div class="col-md-2">Page No</div>
            <div class="col-md-8">{{ $row->page_no }}</div>
        </div>
    </div>

    <div class="list-group-item">
        <div class="row">
            
            <div class="col-md-8">{{ $row->data }}</div>
        </div>
    </div>
    @endforeach
    
</div>

@endsection