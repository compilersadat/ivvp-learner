@extends('layouts.layout')
@section('content')
<div class="row">
    <div class="col">
        @if ($errors->any())
            <div class="alert alert-danger">
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
        <div class="card-header"><strong>Add New Institute</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{ route('institutes.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-control-label">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-control-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-control-label">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="usb_identifier" class="form-control-label">USB Identifier</label>
                    <div class="input-group">
                        <input type="text" id="usb_identifier" name="usb_identifier" value="{{ old('usb_identifier') }}" class="form-control" placeholder="Unique ID embedded on the USB device">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" id="generate-usb-key" data-endpoint="{{ route('institutes.generateUsbKey') }}">
                                Create Key
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">Connect the USB device, then click "Create Key" to generate and store a hardware-bound identifier.</small>
                </div>
                <div class="form-group">
                    <label for="password" class="form-control-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-control-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="card">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('generate-usb-key');
    if (!button) {
        return;
    }

    const input = document.getElementById('usb_identifier');
    const endpoint = button.dataset.endpoint;

    button.addEventListener('click', function () {
        if (!endpoint) {
            return;
        }

        const originalText = button.innerText;
        button.disabled = true;
        button.innerText = 'Generating...';

        fetch(endpoint, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Unable to create key');
                }
                return response.json();
            })
            .then(data => {
                if (data.key) {
                    input.value = data.key;
                }
            })
            .catch(() => {
                alert('Failed to create USB key. Please try again.');
            })
            .finally(() => {
                button.disabled = false;
                button.innerText = originalText;
            });
    });
});
</script>
