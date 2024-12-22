@extends('admin.layout.layout')
@push('styles')
<style type="text/css">
    .ck-editor__editable_inline{
        height: 400px;
    }
    
</style>
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>
    <form class="form" @if(empty($insight['id'])) action="{{ route('admin.add_edit_insight') }}" @else action="{{ route('admin.add_edit_insight', $insight['id']) }}" @endif  method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{request()->route('id')}}">
        <input type="hidden" class="form-control" name="admin_id" id="admin_id" value="{{ Auth::guard('admin')->user()->id }}">
        <div class="form-group">
            <label for="title" class="col-form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title"
                @if(!empty($insight['title'])) value="{{ $insight['title'] }}" @else value="{{ old('title') }}" @endif placeholder="Type Title..."> 
            @error('title')
              <p class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </p>
              @enderror
        </div>
        <div class="form-group row">
            <div class="col-lg-5">
                <label for="image_url" class="col-form-label">Cover Image</label>
                <div class="d-flex justify-content-between align-items-center"> 
                    <div>
                        <!-- actual upload which is hidden -->
                        <input type="file" class="actual-btn" id="image_url" name="image_url" hidden/>
                        @error('image_url')
                          <p class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                          </p>
                        @enderror
                        <!-- our custom upload button -->
                        <label for="image_url" class="btn edit-btn" >Add Image</label>
                        <span style="max-width: 10rem;display: inline-block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" class="file-chosen">No file chosen</span>
                    </div>
                    <div class="append-file">
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <label for="publication_details" class="col-form-label">Publication Details</label>
                <input type="text" class="form-control" id="publication_details" name="publication_details"
                    @if(!empty($insight['publication_details'])) value="{{ $insight['publication_details'] }}" @else value="{{ old('publication_details') }}" @endif  placeholder="by MatchCampus | Jun 26, 2023 | Study, USA ">
                @error('publication_details')
                  <p class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                  </p>
                @enderror
            </div>
            
        </div>
         <div class="form-group">
            <label for="short_details" class="col-form-label">Short Description</label>
            @error('short_details')
              <p class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </p>
            @enderror
            <textarea class="form-control" id="short_details" rows="4" name="short_details" placeholder="Type description...">@if (!empty($insight['short_details'])){!! $insight['short_details'] !!}@else {!! old('short_details') !!} @endif
            </textarea>
        </div>               
        <div class="form-group">
            <label for="details" class="col-form-label">Description</label>
            @error('details')
              <p class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </p>
            @enderror
            <textarea class="ckeditor form-control" id="details" name="details" placeholder="Type description...">
                @if (!empty($insight['details'])){!! $insight['details'] !!}@else {!! old('details') !!} @endif
            </textarea>
        </div>
                        
                
        <button type="button" class="btn btn-outline-secondary">Cancel</button>
            &emsp;
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@push('scripts')
<!-- <script src="{{ url('admin/js/ckeditor/ckeditor.js') }}"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>

    ClassicEditor
        .create( document.querySelector( '#details' ),{
            ckfinder:{
                uploadUrl:"{{route('ckeditor.upload_image',['_token'=>csrf_token()])}}",
            },
            image: {
                toolbar: [ 'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', '|','toggleImageCaption', 'imageTextAlternative' ]
            }
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
@endpush
@endsection