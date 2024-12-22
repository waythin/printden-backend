@extends('admin.layout.layout')
@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('something.css') }}"> --}}
@endpush
@section('title', $title)
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>
    {{-- Content Starts Here --}}
    
    <form class="user" action="{{ route('admin.change.password') }}" method="post">
        @csrf
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if (Session::has('error_message'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> {{ Session::get('error_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (Session::has('success_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success:</strong> {{ Session::get('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ $adminDetails->email }}" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label>Admin Type</label>
                            <input type="text" class="form-control" value="{{ $role->name }}" readonly="">
                        </div> --}}
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password"
                                name="current_password"placeholder="Current Password">
                            <span id="check_password"></span>
                            @error('current_password')
                            <span class="pl-2 text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input minlength="6" type="password" class="form-control" id="new_password" name="password"
                                placeholder="New Password">
                            @error('password')
                            <span class="pl-2 text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input minlength="6" type="password" class="form-control" id="confirm_password"
                                name="password_confirmation" placeholder="Password">
                                @error('password_confirmation')
                                <span class="pl-2 text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Publish
                </div>
                <div class="card-body">
                    <div class="button-class">
                        <button type="submit" class="btn btn-info btn-icon-split mr-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span> <span class="text">Save</span> </button>
                        <button type="reset" class="btn btn-light"><i class="fa fa-ban" aria-hidden="true"></i>
                            Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    @push('scripts')
    @endpush
@endsection
