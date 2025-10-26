@extends('admin.layout.layout')
@push('styles')
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button class="btn mt-4 mt-sm-0 add-btn gallery" module="Gallery" type="button" data-toggle="modal" data-target="#form_modal">
            <img class="img-fluid" src="{{asset('admin/img/icons/plus-white.svg')}}" alt="plus"> Add Gallery
        </button>
    </div>
    {{-- modal start --}}

        <div class="modal fade form_modal" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="form_modal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pl-2">

                    <div class="modal-header">
                        <h5 class="modal-title pt-3" id=""> Add Gallery </h5>
                    </div>
                    <div class="modal-body">
                        <form class="form" action="{{ route('admin.post.gallery') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="col-form-label" for="event_id">Event <span class="mandatory">*</span></label>
                                <select class="form-control" name="event_id" required>
                                    @foreach ($events as $key => $event)
                                        <option value="{{ $event->id }}" {{ old('event_id', $gallery['event_id'] ?? '') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="event_category_id">Event <span class="mandatory">*</span></label>
                                <select class="form-control" name="event_category_id" required>
                                    @foreach ($categories as $key => $cat)
                                        <option value="{{ $cat->id }}" {{ old('event_category_id', $gallery['event_category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->title }}
                                        </option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image_url" class="col-form-label">Images</label>
                                <input type="file" class="form-control" id="imageurl" name="image_url[]" multiple required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary modal-btn"
                            data-dismiss="modal">Cancel</button>
                            &emsp;
                        <button type="submit" id="saveBtn" class="btn btn-primary modal-btn">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- modal end --}}
        
    <div class="row padding-top">
        <div class="col-lg-12">
            @if(!empty($events))
            <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($galleries as $key => $gal)
                    <tr>
                        <td>{{ $gal['event']['title'] }}</td>
                        <td>{{ $gal['category']['title'] }}</td>
                        <td>
                            <img src="{{ asset($gal['image_url']) }}" class="img-thumbnail" alt="" style="max-width: 7rem;">
                        </td>
                        <td>
                            <div class="action-list">
                                <select class="form-control w-75 updateStatus" module="gallery" data_id="{{ $gal['id'] }}" data_admin_id={{ Auth::guard('admin')->user()->id }}>
                                    <option value="active"
                                        @if (!empty($gal['status']) && $gal['status'] == 'active') selected @endif>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        @if (!empty($gal['status']) && $gal['status'] == 'inactive') selected @endif>
                                        Inactive
                                    </option>
                                   
                                </select>
                            </div>
                        </td> 
                        <td>
                            <button class="btn show-btn edit-btn click-check" module="gallery" type="button"
                                data-id="{!! $gal['id'] !!}" data-toggle="modal"
                                data-target=".form_modal" data-url="{{ route('admin.post.gallery', $gal['id']) }}">Edit
                            </button>

                            <a title="Delete Event" href="javascript:void(0)" class="confirmDelete" module="Event" moduleid="{{ $gal['id'] }}">
                                <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <h6 class="padding-top">No Image Available</h6>
        @endif
        </div>
    </div>
@push('scripts')
@endpush
@endsection