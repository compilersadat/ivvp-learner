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
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        @include('partials.alerts')
        <div class="card-header"><strong>Create Study Material Folder</strong></div>
        <div class="card-body card-block">
            <form method="POST" action="{{ route('study-materials.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-control-label">Folder Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Faculty</label>
                    <select class="form-control" id="faculty" name="faculty_id" required>
                        <option value="">-- Select Faculty --</option>
                        @foreach($faculties as $opt)
                            <option value="{{ $opt->faculty_id }}" {{ old('faculty_id') === $opt->faculty_id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Branch</label>
                    <select class="form-control" id="branch" name="branch_id" required>
                        <option value="">-- Select Branch --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Year</label>
                    <select class="form-control" id="year" name="year" required>
                        <option value="">-- Select Year --</option>
                    </select>
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
                <div class="card">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    const selectedBranch = @json(old('branch_id'));
    const selectedYear = @json(old('year'));

    $('#faculty').on('change', function(e){
        var fac = e.target.value;
        if(!fac){
            $('#branch').html('<option value="">-- Select Branch --</option>');
            $('#year').html('<option value="">-- Select Year --</option>');
            return;
        }
        let url = '{{ route('ajax-get-trends') }}'+'?fac='+fac;
        $.get(url, function(data){
            $('#branch').empty();
            $('#branch').append('<option value="">-- Select Branch --</option>');
            $.each(data, function(index, obj){
                let isSelected = selectedBranch && selectedBranch === obj.branch_id ? 'selected' : '';
                $('#branch').append('<option value="'+obj.branch_id+'" '+isSelected+'>'+obj.name+'</option>');
            })
        });

        let year_url = '{{ route('ajax-get-years') }}'+'?fac='+fac;
        $.get(year_url, function(data){
            $('#year').empty();
            $('#year').append('<option value="">-- Select Year --</option>');
            if(data && data.duration){
                for (var i = 1; i <= data.duration; i++) {
                    let isSelected = selectedYear && parseInt(selectedYear) === i ? 'selected' : '';
                    $('#year').append('<option value="'+i+'" '+isSelected+'>'+i+'</option>');
                }
            }
        });
    });

    if($('#faculty').val()){
        $('#faculty').trigger('change');
    }
</script>
@endsection
