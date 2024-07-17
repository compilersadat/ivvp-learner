@extends('layouts.layout')
@section('content')

	<div class=" ">
		<h3 class="h3 my-4 h3-resposive text-center serif">Select Trad</h3>
		<div class="row m-0 p-0">
			@foreach(@$trends as $row)
			@if($row->number!=0)
				<div class="col-md-4  align-self-center" >
					<div class="card   p-2 mb-3 hoverable  text-center serif " style="height: 100%;">
						<a href="{{route('admin.sub.qtn',$row->branch_id)}}" class="black-text">{{$row->name}}&ensp;({{App\Faculty::where('faculty_id',$row->wrtf)->value('name')}})&ensp;<span class="badge badge-pill light-blue">{{App\PracticeQuestion::where('wrtb',$row->branch_id)->count()}}</span></a>
							
						</div>
				</div>
			@endif
			@endforeach
		</div>
	</div>

@endsection