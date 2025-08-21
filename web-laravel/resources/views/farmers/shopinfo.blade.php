@extends('layouts.app')

@section('title','Shop Info')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Seller Onboarding — Shop Info</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('farmer.shopinfo.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Farm / Shop Name</label>
            <input type="text" name="farmname" class="form-control @error('farmname') is-invalid @enderror"
                   value="{{ old('farmname', $farmer->farmname ?? '') }}" required>
            @error('farmname') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Pickup Address</label>
            <input type="text" name="pickup_address" class="form-control @error('pickup_address') is-invalid @enderror"
                   value="{{ old('pickup_address', $farmer->pickup_address ?? '') }}">
            @error('pickup_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Contact Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email ?? '') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Phone</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $user->phone ?? '') }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
            <button class="btn btn-success" type="submit">
                Save & Continue
            </button>
        </div>
    </form>
</div>
@endsection
