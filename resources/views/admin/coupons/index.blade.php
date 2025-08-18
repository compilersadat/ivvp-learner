@extends('layouts.layout')
@section('content')
<div class="content">
  <div class="row">
    <div class="col-md-12 text-left mb-2">
        <a href="{{ route('coupons.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Generate Coupons
        </a>
    
        {{-- Export ALL coupons --}}
        <a href="{{ route('coupons.export.pdf') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-file-pdf-o"></i> Download PDF
        </a>
    
        {{-- Example: Export by package (if you pass ?package_id=) --}}
        {{-- <a href="{{ route('coupons.export.pdf', ['package_id' => 1]) }}" class="btn btn-sm btn-outline-secondary">PDF (Pkg #1)</a> --}}
    </div>
    
  </div>

  <div class="animated fadeIn">
    <div class="row">
      @include('partials.alerts')
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <strong class="card-title">All Coupons</strong>
          </div>
          <div class="card-body">
            <table id="bootstrap-data-table" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>SR NO</th>
                  <th>Code</th>
                  <th>Discount</th>
                  <th>Used</th>
                  <th>Expires At</th>
                  <th>Created</th>
                  <th>Package</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = ($datas->currentPage() - 1) * $datas->perPage() + 1; @endphp
                @foreach($datas as $row)
                  <tr>
                    <td>{{ $i }}</td>
                    <td><code>{{ $row->code }}</code></td>
                    <td>{{ number_format($row->discount, 2) }}</td>
                    <td>
                      @if($row->is_used)
                        <span class="badge badge-danger">Used</span>
                      @else
                        <span class="badge badge-success">Available</span>
                      @endif
                    </td>
                    <td>{{ $row->expires_at ? $row->expires_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                    <td>
                        {{ $row->package ? $row->package->name : '-' }}
                      </td>
                    <td>
                      <a href="{{ route('coupons.delete', $row->id) }}"
                         onclick="return confirm('Delete this coupon?');"
                         class="label bg-red-active">
                         <i class="fa fa-trash fa-1x" style="color:#000"></i>
                      </a>
                      <a href="{{ route('coupons.edit', $row->id) }}" class="label">
                        <i class="fa fa-edit fa-1x" style="color:#000"></i>
                      </a>
                    </td>
                  </tr>
                  @php $i++; @endphp
                @endforeach
              </tbody>
            </table>

            <div class="mt-3">
              {{ $datas->withQueryString()->links() }}
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- If you’re using DataTables assets, include like your example --}}
<script src="{{ asset('js/lib/data-table/datatables.min.js')}}"></script>
<script src="{{ asset('js/lib/data-table/dataTables.bootstrap.min.js')}}"></script>
<script>
  $(function(){ $('#bootstrap-data-table').DataTable(); });
</script>
@endsection
