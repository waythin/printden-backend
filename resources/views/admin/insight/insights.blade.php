@extends('admin.layout.layout')
@push('styles')
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <a target="_blank" href="{{ route('admin.add_edit_insight') }}" class="btn mt-4 mt-sm-0 add-btn click-check" module="Insight" >
            <img class="img-fluid" src="{{asset('admin/img/icons/plus-white.svg')}}" alt="plus"> Add Insight
        </a>
    </div>
    <div class="row padding-top">
        <div class="col-lg-12">
            @if(!empty($insights))
            <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th span="1" style="width: 20%;">Title</th>
                        <th span="1" style="width: 10%;">Image</th>
                        <th span="1" style="width: 15%;">Short Details</th><!-- 
                        <th span="1" style="width: 25%;">Description</th> -->
                        <th span="1" style="width: 15%;">Status</th>
                        <th span="1" style="width: 15%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($insights as $key => $insight)
                    <tr>
                        <td>{{ $insight['title'] }}</td>
                        <td>
                             @if (!empty($insight['image_url']))
                                <img class="img-fluid" style="width: 5rem;" src="{{asset($insight['image_url'])}}" alt="{{ $insight['title'] }}">
                            @else
                                N/A
                            @endif 
                        </td>
                        <td>{!! Str::limit($insight['short_details'], 100, $end='....') !!}</td>
                        <td>
                            <div class="action-list">
                                <select class="form-control w-75 updateStatus" module="insight" data_id="{{ $insight['id'] }}" data_admin_id={{ Auth::guard('admin')->user()->id }}>
                                    <option value="active"
                                        @if (!empty($insight['status']) && $insight['status'] == 'active') selected @endif>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        @if (!empty($insight['status']) && $insight['status'] == 'inactive') selected @endif>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </td>
                        <td>
                            
                            <a href="{{ route('admin.add_edit_insight', $insight['id']) }}" class="btn edit-btn mr-3" >Edit</a>
                            <a title="Delete Insight" href="javascript:void(0)" class="confirmDelete" module="insight" moduleid="{{ $insight['id'] }}"><i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i></a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <h6 class="padding-top">No Insight Available</h6>
        @endif
        </div>
    </div>
@push('scripts')
@endpush
@endsection