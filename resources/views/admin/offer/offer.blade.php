@extends('admin.layout.layout')
@push('styles')
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button class="btn mt-4 mt-sm-0 add-btn offer" module="Offer" type="button" data-toggle="modal" data-target="#form_modal">
            <img class="img-fluid" src="{{asset('admin/img/icons/plus-white.svg')}}" alt="plus"> Add Offer
        </button>
    </div>
    {{-- modal start --}}

        <div class="modal fade form_modal" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="form_modal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pl-2">

                    <div class="modal-header">
                        <h5 class="modal-title pt-3" id=""> Add Offer </h5>
                    </div>
                    <div class="modal-body">
                        <form class="form" action="{{ route('admin.post.offer') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- <input type="hidden" class="form-control" name="id" id="id" value="">
                            <input type="hidden" class="form-control" name="admin_id" id="admin_id" value="{{ Auth::guard('admin')->user()->id }}"> --}}


                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title') }}" placeholder="Type title..."> 
                            </div>
                            <div class="form-group">
                                <label for="discount" class="col-form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    value="{{ old('discount') }}" placeholder="Type discount...">
                            </div>
                            <div class="form-group">
                                <label for="start_date" class="col-form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}" placeholder="Type start date...">
                            </div>
                            <div class="form-group">
                                <label for="end_date" class="col-form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ old('end_date') }}" placeholder="Type end date...">
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
            @if(!empty($offers))
            <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Discount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Validity</th>
                        {{-- <th>Status</th> --}}
                        {{-- <th>Action</th> --}}
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offers as $key => $offer)
                    <tr>
                        <td>{{ $offer['title'] }}</td>
                        <td>
                            @if(!empty($offer['discount']))
                                {{ $offer['discount'] }}%
                            @else N/A
                            @endif
                        </td>
                        <td>
                            @if(!empty($offer['start_date']))
                                {{ date('d M Y', strtotime($offer['start_date'])) }}
                            @else N/A
                            @endif
                        </td>
                        <td>
                            @if(!empty($offer['end_date']))
                                {{ date('d M Y', strtotime($offer['start_date'])) }}
                            @else N/A
                            @endif
                        </td>

                       
                        <td>
                            @if(!empty($package['discount_validity']))
                                {{ date('d M Y', strtotime($package['discount_validity'])) }}
                            @else N/A
                            @endif
                        </td>
                        {{-- <td>
                            <div class="action-list">
                                <select class="form-control w-75 updateStatus" module="offer" data_id="{{ $offer['id'] }}" data_admin_id={{ Auth::guard('admin')->user()->id }}>
                                    <option value="active"
                                        @if (!empty($offer['status']) && $offer['status'] == 'active') selected @endif>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        @if (!empty($offer['status']) && $offer['status'] == 'inactive') selected @endif>
                                        Inactive
                                    </option>
                                   
                                </select>
                            </div>
                        </td> --}}
                        {{-- <td>
                            <button class="btn show-btn edit-btn click-check" module="offer" type="button" data-whatever="{{ $offer['cupon_code'] }}"
                                data-id="{!! $offer['id'] !!}" data-toggle="modal"
                                data-target=".form_modal" data-url="{{ route('admin.add_edit_offer', $offer['id']) }}">Edit
                            </button>

                            <a title="Delete Package" href="javascript:void(0)" class="confirmDelete" module="Offer" moduleid="{{ $offer['id'] }}">
                                    <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                            </a>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <h6 class="padding-top">No Offer Available</h6>
        @endif
        </div>
    </div>
@push('scripts')
@endpush
@endsection