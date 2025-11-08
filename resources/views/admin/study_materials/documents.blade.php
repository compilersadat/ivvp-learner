@extends('layouts.layout')
@section('content')
<div class="content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('partials.alerts')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('study-materials.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back to folders</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><strong>Folder Details</strong></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $folder->name }}</p>
                    <p><strong>Faculty:</strong> {{ @App\Models\Faculty::where('faculty_id', $folder->faculty_id)->value('name') }}</p>
                    <p><strong>Branch:</strong> {{ @App\Models\Branch::where('branch_id', $folder->branch_id)->value('name') }}</p>
                    <p><strong>Year:</strong> {{ $folder->year }}</p>
                    <p><strong>Status:</strong> {{ $folder->is_active ? 'Active' : 'Inactive' }}</p>
                    <p><strong>Description:</strong> {{ $folder->description ?? 'NA' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><strong>Add Document</strong></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('study-materials.documents.store', $folder) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label">Document Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">S3 URL</label>
                            <input type="text" name="file_url" class="form-control" value="{{ old('file_url') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Document</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"><strong>Documents</strong></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>URL</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $index => $document)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td><a href="{{ $document->file_url }}" target="_blank">View</a></td>
                                    <td>
                                        <span class="badge {{ $document->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $document->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('study-materials.documents.delete', $document) }}" onclick="return confirm('Delete this document?');" class="label">
                                            <i class="fa fa-trash fa-1x" style="color:#000"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No documents added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
