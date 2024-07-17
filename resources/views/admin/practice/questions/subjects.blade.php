@extends('layouts.layout')
@section('content')
<div class=" mt-3 ">
    <h3 class="h3 h3-resposive text-center serif">Select Chapter</h3>
    <div class="row px-3  ">
        @foreach(@$sub as $row)
        @if($row->number!=0)
            <div class="col-md-3">
                <div class="card  p-2 mb-3 align-self-center hoverable text-center serif " >
                    <a href="{{route('practice.questions.question',$row->sub_id)}}" class="black-text">{{$row->name}}&ensp;<span class="badge badge-pill light-blue">{{App\Models\PracticeQuestion::where('wrts',$row->sub_id)->count()}}</span></a>
                        
                    </div>
            </div>
        @endif
        @endforeach
    </div>
</div>
@endsection