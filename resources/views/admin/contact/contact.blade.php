@extends('admin.layout.layout')

@push('styles')
    <!-- Include any additional CSS here -->
@endpush

@section('title', $title)

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <div class="d-flex mt-3">
            <!-- Additional buttons or links can go here -->
        </div>
    </div>

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
                            <th scope="col">phone</th>
                            <th scope="col">Message</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Updated At</th>
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

                var url = '{{ route('admin.contact_datatables') }}';

                var table = $('#dataTable').DataTable({
                    processing: true,
                    language: { processing: '<div class="lds-dual-ring"></div>' },
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
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'phone', name: 'phone' },
                        { data: 'message', name: 'message' },
                        { data: 'date', name: 'date' },
                        { data: 'status', name: 'status' },
                        { data: 'updated_date', name: 'updated_date' },
                        // { data: 'discounted_price', name: 'discounted_price' },
                        // { data: 'expire_date', name: 'expire_date' },
                        // { data: 'status', name: 'status', orderable: false, searchable: false },
                        // { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                $('.search').keyup(function() {
                    table.search($(this).val()).draw();
                });





                $(document).on('change', '.contact-status-dropdown', function () {
                var id = $(this).data('id'); // Get order ID from data attribute
                var status = $(this).val(); // Get the selected status

                // Send an AJAX request to update the status
                $.ajax({
                    url: '{{ route("admin.contact.updateStatus") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                        id: id,
                        status: status
                    },
                    success: function (response) {
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
                    error: function (xhr) {
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
