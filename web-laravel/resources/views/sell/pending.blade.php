@extends('layouts.app')
@section('title','Application Pending')

@section('content')
<div class="text-center py-5">
  <h4>Your farmer application is under review</h4>
  <p class="text-muted">
    Thank you! A Cooperative Admin will review your details.
    You’ll be notified once your account is approved.
  </p>

  <div class="mt-3">
    <a class="btn btn-outline-secondary" href="{{ route('customer.products.index') }}">Back to shopping</a>
  </div>
</div>
@endsection
