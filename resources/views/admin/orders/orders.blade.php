@extends('admin.layout.layout')
@push('styles')
<style>
</style>
@endpush
@section('title', $title)
@section('content')
<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>
    <div class="row justify-content-center padding-top">
        <div class="col-lg-12 padding-bottom">
            <div class="d-flex justify-content-between align-items-center padding-bottom">
                <!-- Buttons in the center -->
                <div class="mx-auto">
                    <button type="button" class="status-toggle btn active w-auto" data_type="active">Active</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="delivered">Delivered</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="canceled">Canceled</button>
                    <button type="button" class="status-toggle btn w-auto" data_type="all">All</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-flex justify-content-between  padding-bottom">
            <div class="search-div">
                <form class="form-inline">
                    <div class="input-group search-btn">
                        <input type="text" class="form-control search border-0 small" placeholder="Search here.."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn p-0" type="button">
                                <img class="img-fluid" src="{{ asset('admin/img/icons/search.svg') }}" alt="search">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <form class="filter-form" action="#" method="get">
                @csrf
                <div class="form-group mr-2">
                    <input type="text" class="form-control" name="daterange" id="date_range_value" placeholder="Date Range" value="{{ old('daterange') }}" />
                </div>
                <div class="form-group">
                    {{-- <button type="submit" class="btn submit_btn">Filter</button> --}}
                    <button type="button" class="btn btn-secondary clear_btn"><i style="color: #fff; font-size:1rem" class="fa fa-retweet"></i></button>
                </div>
            </form>
        </div>
        
        <div class="col-lg-12">
            <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th span="1" style="width: 5%;"><input type="checkbox" id="select-all"></th>
                        <th span="1" style="width: 10%;">Order Date</th>
                        <th span="1" style="width: 10%;">Order Id</th>
                        <th span="1" style="width: 10%;">Store Details</th>
                        <th span="1" style="width: 18%;">Customer Info</th>
                        <th span="1" style="width: 12%;">Status</th>
                        <th span="1" style="width: 10%;">COD</th>
                        <th span="1" style="width: 10%;">Delivery Charge</th>
                        <th span="1" style="width: 14%;"> Action</th>
                        <th span="1" style="width: 14%;"  class="print-only"> Income</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                        <td id="total-amount">0.00</td>
                        <td id="total-delivery-charge">0.00</td>
                        <td id="total-income">0.00</td>
                        <td class="print-only">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div>
    </div>
@push('scripts')
<script>
    $(document).ready(function() {
        @if(session('success_message'))
            showToast('success_message', "{{ session('success_message') }}");
        @endif

        var table = $('#dataTable').DataTable({
            drawCallback: function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    },
            processing: true,
            //serverSide: true,
            "scrollY": "600px",
            "scrollCollapse": true,
            paging: false,       // Disable pagination
            "language": {
                processing: '<div class="lds-dual-ring"></div>'
            },
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: generateDataTableUrl(),
                type: 'post',
                data: function (d) {
                    d.daterange = $('#date_range_value').val(); 
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                }
            },
            columnDefs: [
                
            ],
            columns: [
                { data: null, searchable: false, orderable: false  },
                { data: 'created_at' },
                { data: 'order_no' },
                { data: 'customer_id' },
                { data: 'service_id' }, 
                { data: 'album_id' }, 
                { data: 'status', searchable: false, orderable: false},
                { data: 'action', searchable: false, orderable: false }
            ],
            order: [[1, 'desc']], // Order by created_at in descending order
            dom: 'lrtip', // Enables the Buttons extension

        });
    // Handle Filter Form Submission
    $(document).on("submit", ".filter-form", function (e) {
        e.preventDefault();
        // Get form data
        var formData = {
            daterange: $('#date_range_value').val()
        };
        //console.log(formData);
        // Reload the DataTable with the new filters
        table.ajax.reload();
    });
    // Auto-trigger the form submission when product type or date range changes
    $(document).on("change", "#type, #date_range_value", function () {
        $(".filter-form").trigger("submit");
    });
    // Handle Clear Button Click
    $(document).on("click", ".clear_btn", function() {
        $('#date_range_value').val('');
        table.ajax.reload();
    });
    // Highlight search terms on table draw
    table.on('draw', function() {
        var body = $(table.table().body());
        body.unhighlight();

        // Get the current search term and highlight it
        var searchTerm = table.search();
        if (searchTerm) {
            body.highlight(searchTerm); // Applies highlight to search term
        }
    });
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
        
    }, function(start, end, label) {
        $('input[name="daterange"]').val(start.format('MM/DD/YYYY')+" - "+end.format('MM/DD/YYYY'));
        $( "#date_range_value" ).trigger( "change" );
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });


    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });


    // Button click event to change status and reload DataTable
    $(document).on("click", ".status-toggle", function () {
        $(".status-toggle").removeClass("active");
        $(this).addClass("active");
        selectedType = $(this).attr('data_type');
        reloadDataTable();
    });
    //datatable search
    $('.search').keyup(function() {
        table.search($(this).val()).draw();
    })

    // Function to reload DataTable with current type and courier
    function reloadDataTable() {
        $('.filter-form')[0].reset();
        var url = generateDataTableUrl(); 
        table.ajax.url(url).load();
    }
    
    function generateDataTableUrl() {
        var url = '{{ route('admin.orders_datatables') }}';
        return url;
    }
});

</script>
@endpush
@endsection