@extends('admin.layout.layout')
@push('styles')
<style>
    .table tbody tr:last-child td {
        border-bottom: none; /* Removes bottom border for the last row */
    }
    </style>
@endpush
@section('title', $title)
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>
    <div class="row justify-content-center padding-top">
        {{-- <div class="col-lg-12 padding-bottom">
            <div class="d-flex justify-content-between align-items-center padding-bottom">
                <!-- Buttons in the center -->
                <div class="mx-auto">
                    <button type="button" class="status-toggle btn active w-auto" data_type="active">Active</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="delivered">Delivered</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="canceled">Canceled</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="all">All</button>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12 d-flex justify-content-between  padding-bottom">
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
        
            {{-- <form class="filter-form" action="#" method="get">
                @csrf
                <div class="form-group mr-2">
                    <input type="text" class="form-control" name="daterange" id="date_range_value"
                        placeholder="Date Range" value="{{ old('daterange') }}" />
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary clear_btn"><i style="color: #fff; font-size:1rem"
                            class="fa fa-retweet"></i></button>
                </div>
            </form> --}}
        </div>

        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            {{-- <th span="1" style="width: 5%;"><input type="checkbox" id="select-all"></th> --}}
                            <th span="1" style="width: 8%;">Order No</th>
                            <th span="1" style="width: 8%;">Date</th>
                            <th span="1" style="width: 8%;">Service</th>
                            <th span="1" style="width: 10%;">Customer Info</th>
                            <th span="1" style="width: 10;">Order Status</th>
                            <th span="1" style="width: 10%;">Payment Status</th>
                            <th span="1" style="width: 6%;">Method</th>
                            <th span="1" style="width: 10%;">Delivery Charge</th>
                            <th span="1" style="width: 12%;">Amount</th>
                            <th span="1" style="width: 8%;">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- custom modal pop up --}}
    <div class="modal fade form_modal" id="order_details_modal" tabindex="-1" role="dialog"
        aria-labelledby="form_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content pl-2">

            </div>
        </div>
    </div>
    {{-- custom modal pop up end --}}



    @push('scripts')
        <script>
            $(document).ready(function() {
                @if (session('success_message'))
                    showToast('success_message', "{{ session('success_message') }}");
                @endif


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


                var url = '{{ route('admin.orders_datatables', 'all') }}';
                var table = $('#dataTable').DataTable({
                    drawCallback: function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    },


                    processing: true,
                    language: { processing: '<div class="lds-dual-ring"></div>' },
                    serverSide: true,
                    searching: true,
                    lengthChange: false,
                    dom: 'lrtip',
                    
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        type: 'get',
                        data: function(d) {
                            d.daterange = $('#date_range_value').val();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                        }
                    },
                    columnDefs: [

                    ],
                    columns: [
                        // { data: null, searchable: false, orderable: false  },
                        {
                            data: 'order_details',
                            name: 'order_details',
                            searchable: true 
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'service',
                            name: 'service'
                        },
                        {
                            data: 'customer_info',
                            name: 'customer_info'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status'
                        },
                        {
                            data: 'payment_method',
                            name: 'payment_method'
                        },
                        {
                            data: 'delivery_charge',
                            name: 'delivery_charge'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'action',
                            searchable: false,
                            orderable: false
                        }
                    ],
                    order: [
                        [1, 'desc']
                    ], // Order by created_at in descending order
                    dom: 'lrtip', // Enables the Buttons extension

                });

                $('.search').on('keyup', function() {
                    table.search($(this).val()).draw();
                });



                // order pop up
                $(document).on('click', '.order_details', function () {
                var url = $(this).data('url'); // Get the URL from data attribute

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#order_details_modal .modal-content').html(response.html); // Load content into modal
                        $('#order_details_modal').modal('show'); // Show the modal
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr.statusText);
                    }
                });
            });

             // Listen for changes on the dropdown
            $(document).on('change', '.status-dropdown', function () {
                var orderId = $(this).data('id'); // Get order ID from data attribute
                var status = $(this).val(); // Get the selected status

                // Send an AJAX request to update the status
                $.ajax({
                    url: '{{ route("admin.orders.updateStatus") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                        order_id: orderId,
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





                // Listen for changes on the payment status dropdown
                $(document).on('change', '.payment-status-dropdown', function () {
                    var orderId = $(this).data('id'); // Get order ID from data attribute
                    var paymentStatus = $(this).val(); // Get the selected payment status

                    // Send an AJAX request to update the payment status
                    $.ajax({
                        url: '{{ route("admin.orders.updatePaymentStatus") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Include CSRF token
                            order_id: orderId,
                            payment_status: paymentStatus
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
