@extends('admin.layout.layout')
@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('something.css') }}"> --}}
@endpush
@section('title', $title)
@section('content')

{{-- Content Starts Here --}}
<!-- 404 Error Text -->
<div class="text-center">
    <div class="error mx-auto" data-text="404">404</div>
    <p class="lead text-gray-800 mb-5">Page Not Found</p>
    <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
    <a href="{{route('admin.dashboard')}}">&larr; Back to Dashboard</a>
</div>

@push('scripts')
@endpush
@endsection