@extends('layouts.app')

@section('title','Application Under Review')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-2">Thanks! Your seller application is under review</h1>
            <p class="text-muted mb-3">
                Our team will verify your details. You’ll receive an email once your account is approved.
            </p>
            <ul class="mb-4">
                <li>Typical review time: 1–3 business days</li>
                <li>Make sure your email and phone are correct in <a href="{{ route('farmer.shopinfo') }}">Shop Info</a></li>
            </ul>
            <a href="{{ route('home') }}" class="btn btn-success">Go to Home</a>
        </div>
    </div>
</div>
@endsection
