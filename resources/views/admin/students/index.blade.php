@extends('layouts.layout')
@section('content')

<strong class="card-title">All student List</strong>

  <div class="content">
     <div class="row">
    <div class="col-md-12 text-left">
           
          </div>
</div>
            <div class="animated fadeIn">
                <div class="row">
                    @include('partials.alerts')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">All student List</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>SR NO</th>
                                            <th>name</th>
                                            <th>email</th>
                                            <th>phone</th>
                                            <th>faculty</th>
                                            <th>branch</th>
                                            <th>collage</th>
                                            <th>instructor</th>
                                            <th>district</th>
                                            <th>year</th>
                                            <th>m_toung</th>
                                            <th>image</th>
                                            <th>first</th>
                                            <th>paid</th>


                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php $i=1;?>
						              @foreach($students as $student)
						              <tr>
						               <td>{{$i}}</td>
                                       <td>{{$student->name}}</td>
                                        <td>{{$student->email}}</td>
                                        <td>{{$student->phone}}</td>
                                        <td>{{$student->faculty}}</td>
                                        <td>{{$student->branch}}</td>
                                        <td>{{$student->collage}}</td>
                                        <td>{{$student->instructor}}</td>
                                        <td>{{$student->district}}</td>
                                        <td>{{$student->year}}</td>
                                        <td>{{$student->m_toung}}</td>
                                        <td>{{$student->image}}</td>
                                        <td>{{$student->first}}</td>
                                        <td>{{$student->paid}}</td>
                
						               <td>
						                  {{-- <a href="{{route('student.edit',$student->id)}}" class="label  "><i class="fa fa-edit fa-1x" style="color: #000"></i> </a>
						                  <a href="{{route('student.delete',$student->id)}}" onclick="return confirm('Are you sure you want to delete this item?');" class="label bg-red-active"><i class="fa fa-trash  fa-1x" style="color: #000"></i> </a> --}}
						                  {{-- <a href="{{route('student.show',$student->id)}}"><span class="label "><i class="fa fa-eye  fa-1x" style="color: #000"></i>&ensp;</span></a> --}}
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
