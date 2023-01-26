@extends('layouts.layout')
@section('content')

  <div class="content">
     <div class="row">
    <div class="col-md-12 text-left">
            <a href="{{route('packages.create')}}" class=" btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create Package </a><br>
          </div>
</div>
            <div class="animated fadeIn">
                <div class="row">
                    @include('partials.alerts')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">All Package</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>SR NO</th>
                                            <th>Package Name</th>
                                            <th>Number Of Months</th>
                                            <th>Price</th>


                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php $i=1;?>
						              @foreach($datas as $row)
						              <tr>
						               <td>{{$i}}</td>
                                       <td>{{$row->name}}</td>
						               <td>{{$row->number}}</td>
                                       <td>{{$row->price}}</td>
						               <td>
						                  <a href="{{route('packages.delete',$row->id)}}" onclick="return confirm('Are you sure you want to delete this item?');" class="label bg-red-active"><i class="fa fa-trash  fa-1x" style="color: #000"></i> </a>
						                  <a href="{{route('packages.edit',$row->id)}}" class="label  "><i class="fa fa-edit fa-1x" style="color: #000"></i> </a>
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
