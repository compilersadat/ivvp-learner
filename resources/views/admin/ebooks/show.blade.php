@extends('layouts.layout')
@section('content')

 <div class="col-md-6">
            <h3 class="card-title">Book Details:</h3>
 </div>
<div class="list-group">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Book Type</div>
                <div class="col-md-8">{{ $ebook->book_type }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Book Name</div>
                <div class="col-md-8">{{ $ebook->name }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Chapter Name</div>
                
                <div class="col-md-4">
                    @foreach(@App\Chapter::where('book_id', $ebook->id)->get() as $row)
                    <a href="{{ route('chapter-data', $row->id)}}">{{$row->chapter_name}}</a>
                    @endforeach
                </div>
               
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Book Image</div>
                <div class="col-md-4"><img src="{{asset('site/storage/app/ebook/'.$ebook->image)}}" class="" width="100"></div>
            </div>
        </div>


        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Description</div>
                <div class="col-md-8">{!! $ebook->description !!}</div>
            </div>
        </div>



        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Created At</div>
                <div class="col-md-8">{{ $ebook->created_at }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Updated At</div>
                <div class="col-md-8">{{ $ebook->updated_at }}</div>
            </div>
        </div>
    </div>

@endsection
