@extends('layouts.layout')
@section('content')

  <div class="content">
     <div class="row">
    <div class="col-md-12 text-left">
            <a href="{{route('content.create')}}" class=" btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create New </a><br>
          </div>
</div>
            <div class="animated fadeIn">
                <div class="row">
                    @include('partials.alerts')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">All Content List</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>SR NO</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Facualty</th>
                                            <th>Year</th>

                                            <th>Trade</th>
                                            <th>Month</th>

                                            <th>File</th>
                                            <th>Status</th>


                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php $i=1;?>
						              @foreach($contents as $content)
						              <tr>
						               <td>{{$i}}</td>
                                       <td>{{isset($content->title) ? ($content->title) : '-'}}</td>
                                       <td>{!! $content->description !!}</td>
						               <td>{{App\Models\Faculty::where('faculty_id',$content->faculty)->value('name')}}</td>
                                       <td>{{$content->year}}</td>
                                       <td>{{App\Models\Branch::where('branch_id',$content->branch)->value('name')}}</td>
                                       <td>{{$content->month}}</td>

                                       <td>Title: {{App\Models\S3upload::where('id',$content->file_url)->value('title')}}
                                        <br>
                                         Type : {{$content->type}}
                                        </td>
                                       <td>{{$content->status==1?"Active":"Inactive"}}</td>
						               <td>
						                  <a href="{{route('content.edit',$content->id)}}" class="label  "><i class="fa fa-edit fa-1x" style="color: #000"></i> </a>
						                  <a href="{{route('content.delete',$content->id)}}" onclick="return confirm('Are you sure you want to delete this item?');" class="label bg-red-active"><i class="fa fa-trash  fa-1x" style="color: #000"></i> </a>
						                  {{-- <a href="{{route('content.show',$content->id)}}"><span class="label "><i class="fa fa-eye  fa-1x" style="color: #000"></i>&ensp;</span></a> --}}
						               </td>
						              </tr>
						            <?php $i++?>
						             @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- .animated -->
        </div>
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

@endsection
