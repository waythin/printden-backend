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
    
    <form class="user" action="{{ route('admin.update.details') }}" method="post" enctype="multipart/form-data">
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
                    @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label>Admin Type</label>
                            <input type="text" class="form-control" value="{{ $role->name }}" readonly="">
                        </div> --}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name"
                                name="name"placeholder="Name" value="{{ Auth::guard('admin')->user()->name }}">
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input required type="text" class="form-control" id="mobile"
                                name="mobile"placeholder="Mobile"
                                value="{{ Auth::guard('admin')->user()->mobile }}">
                        </div>
                        <div class="form-group">
                            <label for="photo">Admin Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @if (!empty(Auth::guard('admin')->user()->image))
                                <a target="_blank"
                                    href="{{ url(Auth::guard('admin')->user()->image) }}">View
                                    Image</a>
                            @endif
                        </div>
                        <input type="hidden" name="current_image"
                            value="{{ Auth::guard('admin')->user()->image }}">
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
