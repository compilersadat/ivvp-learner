@extends('layouts.layout')
@section('content')
<div class="col-lg-12">
    <div class="card">
        @include('partials.alerts')
      <div class="card-header"><strong>EDIT CHAPTER</strong></div>
         <div class="card-body card-block">
            <form method="POST" action="{{route('data.update', $data->id)}}" enctype="multipart/form-data">
                @csrf
                 @method('PATCH')
                
                 <div class="form-group">
                    <label for="book" class=" form-control-label">Select Book</label>
                   
                      <select class="form-control" name="ebook_id">
                        <option value="">{{ __('Select Book') }}</option>
                        @foreach(@App\Ebook::all() as $row)
                        <option value="{{$row->id}}" {{($data->ebook_id == $row->id) ? "selected" : ''}}>{{$row->name}}</option>
                        @endforeach
                      </select>
                  </div>

                 <div class="form-group">
                  <label for="chapter" class=" form-control-label">Select Chapter</label>
                 
                    <select class="form-control" name="chapter_id">
                      <option value="">{{ __('Select Chapter') }}</option>
                      @foreach(@App\Chapter::all() as $row)
                      <option value="{{$row->id}}" {{ ($data->chapter_id == $row->id) ? "selected" : ''}}>{{$row->chapter_name}}</option>
                      @endforeach
                    </select>
                </div>

               

                <div class="form-group">
       
                    <label for="pNO" class=" form-control-label">Page Number</label>
                   <input type="number" id="pNo" name="page_no" placeholder="Page Number" class="form-control" value="{{$data->page_no}}">
                </div>
           
                
           
                <div class="form-group">
                    <label class="form-label">Data</label>
                    <textarea id="editor1" class="form-control" name="data"
                              placeholder="Enter Data">
                              {{$data->data}}
                    </textarea>
                </div>
     <div class="card">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

                                            
     </div>
 </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>

@endsection