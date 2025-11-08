@extends('layouts.layout')
@section('content')
<div class="content">
    <div class="row mb-3">
        <div class="col-md-12 text-left">
            <a href="{{ route('institutes.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Create Institute
            </a>
        </div>
    </div>
    <div class="animated fadeIn">
        <div class="row">
            @include('partials.alerts')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">All Institutes</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($institutes as $index => $institute)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $institute->name }}</td>
                                        <td>{{ $institute->email }}</td>
                                        <td>{{ $institute->phone ?? 'NA' }}</td>
                                        <td>
                                            <span class="badge {{ $institute->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $institute->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('institutes.status', $institute) }}" class="label">
                                                <i class="fa fa-toggle-{{ $institute->is_active ? 'off' : 'on' }} fa-1x" style="color:#000"></i>
                                            </a>
                                            <a href="{{ route('institutes.edit', $institute) }}" class="label">
                                                <i class="fa fa-edit fa-1x" style="color:#000"></i>
                                            </a>
                                            <a href="{{ route('institutes.delete', $institute) }}" onclick="return confirm('Are you sure you want to delete this institute?');" class="label">
                                                <i class="fa fa-trash fa-1x" style="color:#000"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    });
</script>
@endsection
