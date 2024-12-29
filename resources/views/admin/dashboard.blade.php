@extends('admin.layout.layout')
@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('something.css') }}"> --}}
@endpush
@section('title', $title)
@section('content')
    <!-- Page Heading -->
        <div class="row">
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="dashboard-card card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-6">
                                <div class="d-icon-div" style="background: var(--btn-secondary);">
                                    <img class="img-fluid" src="{{ asset('admin/img/icons/store.svg') }}" alt="store">
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <div class="h4 mb-0 d-data">10</div>
                                <div class="d-per-div success" style="background: var(--success-light);">
                                    <h6 class="m-0"><img class="img-fluid"
                                            src="{{ asset('admin/img/icons/success-arrow.svg') }}" alt="success">
                                        <span>2.5%</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-12 pt-4">
                                <div class="d-text mb-1">
                                    Total Orders
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="dashboard-card card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-6">
                                <div class="d-icon-div" style="background: var(--violet);">
                                    <img class="img-fluid" src="{{ asset('admin/img/icons/file.svg') }}" alt="file">
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <div class="h4 mb-0 d-data">20</div>
                                <div class="d-per-div success" style="background: var(--success-light);">
                                    <h6 class="m-0"><img class="img-fluid"
                                            src="{{ asset('admin/img/icons/success-arrow.svg') }}" alt="success">
                                        <span>2.5%</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-12 pt-4">
                                <div class="d-text mb-1">
                                    Delivered Orders
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="dashboard-card card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-6">
                                <div class="d-icon-div" style="background: var(--orange);">
                                    <img class="img-fluid" src="{{ asset('admin/img/icons/list.svg') }}" alt="list">
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <div class="h4 mb-0 d-data">0</div>
                                <div class="d-per-div error" style="background: var(--error-light);">
                                    <h6 class="m-0"><img class="img-fluid"
                                            src="{{ asset('admin/img/icons/error-arrow.svg') }}" alt="loss">
                                        <span>7.5%</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-12 pt-4">
                                <div class="d-text mb-1">
                                    Total Customers
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="dashboard-card card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-4">
                                <div class="d-icon-div" style="background: var(--success);">
                                    <img class="img-fluid" src="{{ asset('admin/img/icons/dollar.svg') }}" alt="coin">
                                </div>
                            </div>
                            <div class="col-8 text-right">
                                <div class="h4 mb-0 d-data">2</span></div>
                                <div class="d-per-div error" style="background: var(--error-light);">
                                    <h6 class="m-0"><img class="img-fluid"
                                            src="{{ asset('admin/img/icons/error-arrow.svg') }}" alt="loss">
                                        <span>7.5%</span>
                                    </h6>
                                </div>
                            </div>

                            <div class="col-12 pt-4">
                                <div class="d-text mb-1">
                                    Revenue
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row padding-top">
            <div class="col-lg-12">
                <h4>Recent Orders</h4>
                @if (!empty($orders) && $orders->count())
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Contact No.</th>
                                    <th>Email Address</th>
                                    {{-- <th>Order Type</th> --}}
                                    <th>Order No</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $order)
                                {{-- @dd($member) --}}
                                    <tr>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->customer->phone }}</td>
                                        <td>{{ $order->customer->email }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        {{-- <td>{{ $order->size->printType->name }}</td> --}}
                                        <td>{{ date('M d, Y', strtotime($order->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h6 class="padding-top">No Orders Available</h6>
                @endif
            </div>
        </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
               
            });
        </script>
    @endpush
@endsection
