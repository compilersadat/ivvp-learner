@extends('layouts.layout')
@section('content')
<div class="row">
   <div class="col">
      @if ($errors->any())
         <div class=" alert alert-danger">
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
      @endif
      @if (session('success'))
         <div class="alert alert-success">
             {{ session('success') }}
         </div>
      @endif
   </div>
</div>

<div class="col-lg-12">
  <div class="card">
    @include('partials.alerts')
    <div class="card-header"><strong>ADD NEW COUPONS</strong></div>
    <div class="card-body card-block">
      <form method="POST" action="{{ route('coupons.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-control-label">Select Package</label>
            <select name="package_id" class="form-control">
              <option value="">-- None (global coupon) --</option>
              @foreach(\App\Models\Package::all() as $package)
                <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->price }})</option>
              @endforeach
            </select>
          </div>
        <div class="form-group">
          <label class="form-control-label">Number of Coupons</label>
          <input type="number" name="number_of_coupons" class="form-control" placeholder="e.g. 100" min="1" value="{{ old('number_of_coupons', 1) }}">
        </div>

        <div class="form-group">
          <label class="form-control-label">Discount</label>
          <input type="number" step="0.01" name="discount" class="form-control" placeholder="e.g. 100.00" value="{{ old('discount') }}">
          <small class="text-muted">Interpret as flat/percent in app logic.</small>
        </div>

        <div class="form-group">
          <label class="form-control-label">Expires At</label>
          <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
        </div>

        <div class="form-group">
          <label class="form-control-label">Prefix (optional)</label>
          <input type="text" name="prefix" class="form-control" placeholder="e.g. WLCM" value="{{ old('prefix') }}">
        </div>

        <div class="form-group">
          <label class="form-control-label">Random Code Length</label>
          <input type="number" name="code_length" class="form-control" min="4" max="32" value="{{ old('code_length', 8) }}">
        </div>

        <div class="card">
          <button type="submit" class="btn btn-primary">Generate</button>
          <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
