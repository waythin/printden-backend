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
                                    Members Acquired
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
                                    Total Groups
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
                                    Meeting Minutes
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
                                    Events
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row padding-top">
            <div class="col-lg-12">
                <h4>Recently Joined Members</h4>
                @if (!empty($members))
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact No.</th>
                                    <th>Email Address</th>
                                    <th>Organization</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $key => $member)
                                {{-- @dd($member) --}}
                                    <tr>
                                        <td>
                                            @if (!empty($member['admin']['image']))
                                                <img class="tbl-img-thumbnail mr-3"
                                                    src="{{ asset($member['admin']['image']) }}" />
                                            @else
                                                {{-- Generate a random background color --}}
                                                @php
                                                    $hue = mt_rand(0, 360); // Random hue
                                                    $saturation = mt_rand(30, 70); // Adjust saturation for softer colors
                                                    $lightness = mt_rand(70, 90); // Adjust lightness for softer colors

                                                    $randomBackgroundColor = "hsl($hue, $saturation%, $lightness%)";
                                                @endphp

                                                @if (!empty($member['admin']['name']))
                                                    {{-- Create an image with the initial letter and random background color --}}
                                                    <img class="rounded-circle"
                                                        style="width: 30px; height: 30px; background-color: {{ $randomBackgroundColor }}; text-align: center; line-height: 30px;"
                                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30'%3E%3Ctext x='50%' y='50%' font-size='12' text-anchor='middle' dy='.3em' fill='%23000000' font-family='Arial, sans-serif'%3E{{ strtoupper(substr($member['admin']['name'], 0, 1)) }}%3C/text%3E%3C/svg%3E"
                                                        alt="">
                                                @endif
                                            @endif
                                            <span><a href="{{ route('admin.settings.memberProfile', [$member['id']]) }}"
                                                    class="company-profile-view"
                                                    target="_blank">{{ $member['admin']['name'] }}</a></span>

                                        </td>
                                        
                                        <td>{{ $member['admin']['mobile'] }}</td>
                                        <td>{{ $member['admin']['email'] }}</td>
                                        <td>{{ $member['organization'] }}</td>
                                        <td>{{ $member['designation'] }}</td>
                                        <td>{{ date('M d, Y', strtotime($member['created_at'])) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h6 class="padding-top">No Members Available</h6>
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
