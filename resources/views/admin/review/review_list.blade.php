@extends('admin.layout.layout')

@push('styles')
    <!-- Include any additional CSS here -->
@endpush

@section('title', $title)

@section('content')
    {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <div class="d-flex mt-3">
            <!-- Additional buttons or links can go here -->
        </div>
    </div> --}}


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button class="btn mt-4 mt-sm-0 add-btn" module="" type="button" data-toggle="modal" data-target="#review_modal">
            <img class="img-fluid" src="{{ asset('admin/img/icons/plus-white.svg') }}" alt="plus"> Add Review
        </button>
    </div>

    {{-- modal start --}}

    <div class="modal fade review_modal" id="review_modal" tabindex="-1" role="dialog" aria-labelledby="review_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content pl-2">

                <div class="modal-header">
                    <h5 class="modal-title pt-3" id=""> Add Review & Rating</h5>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{route('admin.post.review')}}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" name="id" id="id" value="">
                        <input type="hidden" class="form-control" name="admin_id" id="admin_id"
                            value="{{ Auth::guard('admin')->user()->id }}">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Type Customer name...">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Customer Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Type Customer Email...">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rate" class="col-form-label">Customer Rating</label>
                                    <input type="rate" class="form-control" id="rate" name="rate"
                                        value="{{ old('rate') }}" placeholder="Type Customer rating...">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="image" class="col-form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>
                        
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">Comment</label>
                                    <textarea class="form-control" id="" name="comment" placeholder="Type description...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary modal-btn"
                        data-dismiss="modal">Cancel</button>
                    &emsp;
                    <button type="submit" id="saveBtn" class="btn add-btn modal-btn">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal end --}}

    <div class="row justify-content-between align-items-center py-3 px-3">
        <div class="search-div">
            <h6>Search</h6>
            <form class="form-inline">
                <div class="input-group search-btn">
                    <input type="text" class="form-control search border-0 small" placeholder="Search"
                        aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn p-0" type="button">
                            <img class="img-fluid" src="{{ asset('admin/img/icons/search.svg') }}" alt="search">
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row padding-top">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rate</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Image</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>



    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                var url = '{{ route('admin.review_datatables') }}';

                var table = $('#dataTable').DataTable({
                    processing: true,
                    language: {
                        processing: '<div class="lds-dual-ring"></div>'
                    },
                    serverSide: true,
                    searching: true,
                    lengthChange: false,
                    dom: 'lrtip',
                    ajax: {
                        url: url,
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'rate',
                            name: 'rate'
                        },
                        {
                            data: 'comment',
                            name: 'comment'
                        },
                        {
                            data: 'image',
                            name: 'image'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                $('.search').keyup(function() {
                    table.search($(this).val()).draw();
                });

                $(document).on('change', '.review-status-dropdown', function() {
                    var id = $(this).data('id'); // Get order ID from data attribute
                    var status = $(this).val(); // Get the selected status

                    // Send an AJAX request to update the status
                    $.ajax({
                        url: '{{ route('admin.orders.updateReviewStatus') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Include CSRF token
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success toast
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            } else {
                                // Show error toast
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            // Show error toast
                            Toast.fire({
                                icon: 'error',
                                title: 'An error occurred. Please try again.'
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
