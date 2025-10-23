@extends('admin.layout.layout')
@push('styles')
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button class="btn mt-4 mt-sm-0 add-btn category" module="Category" type="button" data-toggle="modal" data-target="#form_modal">
            <img class="img-fluid" src="{{asset('admin/img/icons/plus-white.svg')}}" alt="plus"> Add Category
        </button>
    </div>
    {{-- modal start --}}

        <div class="modal fade form_modal" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="form_modal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pl-2">

                    <div class="modal-header">
                        <h5 class="modal-title pt-3" id=""> Add Category </h5>
                    </div>
                    <div class="modal-body">
                        <form class="form" action="{{ route('admin.post.category') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- <input type="hidden" class="form-control" name="id" id="id" value="">
                            <input type="hidden" class="form-control" name="admin_id" id="admin_id" value="{{ Auth::guard('admin')->user()->id }}"> --}}

                            
                            <div class="form-group">
                                <label class="col-form-label" for="event_id">Event <span class="mandatory">*</span></label>
                                <select class="form-control" name="event_id" required>
                                    @foreach ($events as $key => $event)
                                        <option value="{{ $event->id }}" {{ old('type', $category['event_id'] ?? '') == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }}
                                        </option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <input type="text" class="form-control" name="title"
                                    value="{{ old('title', $category['title'] ?? '') }}" placeholder="Type title..."> 
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">Description</label>
                                <textarea class="form-control"  name="description" placeholder="Type description...">{{ old('description', $category['description'] ?? '') }}</textarea>
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
            @if(!empty($categories))
            <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $cat)
                    <tr>
                        <td>{{ $cat['title'] }}</td>
                        <td>
                            @if(!empty($cat['description']))
                                {{ $cat['description'] }}%
                            @else N/A
                            @endif
                        </td>
                        <td>
                        </td>
                        <td>
                            <div class="action-list">
                                <select class="form-control w-75 updateStatus" module="category" data_id="{{ $cat['id'] }}" data_admin_id={{ Auth::guard('admin')->user()->id }}>
                                    <option value="active"
                                        @if (!empty($cat['status']) && $cat['status'] == 'active') selected @endif>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        @if (!empty($cat['status']) && $cat['status'] == 'inactive') selected @endif>
                                        Inactive
                                    </option>
                                   
                                </select>
                            </div>
                        </td> 
                        <td>
                            <button class="btn show-btn edit-btn click-check" module="category" type="button"
                                data-id="{!! $cat['id'] !!}" data-toggle="modal"
                                data-target=".form_modal" data-url="{{ route('admin.add_edit_event', $cat['id']) }}">Edit
                            </button>

                            <a title="Delete Event" href="javascript:void(0)" class="confirmDelete" module="Event" moduleid="{{ $cat['id'] }}">
                                <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <h6 class="padding-top">No Category Available</h6>
        @endif
        </div>
    </div>
@push('scripts')
@endpush
@endsection