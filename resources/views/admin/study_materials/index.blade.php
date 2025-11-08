@extends('layouts.layout')
@section('content')
<div class="content">
    <div class="row mb-3">
        <div class="col-md-12 text-left">
            <a href="{{ route('study-materials.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Create Folder
            </a>
        </div>
    </div>
    <div class="animated fadeIn">
        <div class="row">
            @include('partials.alerts')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Study Material Folders</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Faculty</th>
                                    <th>Branch</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($folders as $index => $folder)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $folder->name }}</td>
                                        <td>{{ @App\Models\Faculty::where('faculty_id', $folder->faculty_id)->value('name') }}</td>
                                        <td>{{ @App\Models\Branch::where('branch_id', $folder->branch_id)->value('name') }}</td>
                                        <td>{{ $folder->year }}</td>
                                        <td>
                                            <span class="badge {{ $folder->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $folder->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $folder->documents_count }}</td>
                                        <td>
                                            <a href="{{ route('study-materials.documents', $folder) }}" class="label">
                                                <i class="fa fa-folder-open fa-1x" style="color:#000"></i>
                                            </a>
                                            <a href="{{ route('study-materials.edit', $folder) }}" class="label">
                                                <i class="fa fa-edit fa-1x" style="color:#000"></i>
                                            </a>
                                            <a href="{{ route('study-materials.delete', $folder) }}" onclick="return confirm('Delete this folder?');" class="label">
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
