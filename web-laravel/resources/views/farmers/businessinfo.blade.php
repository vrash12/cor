@extends('layouts.app')

@section('title','Business Info')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Seller Onboarding — Business Info</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('farmer.businessinfo.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Registered Business Name</label>
            <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror"
                   value="{{ old('business_name', $farmer->business_name ?? '') }}" required>
            @error('business_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Registered Address</label>
            <input type="text" name="registered_address" class="form-control @error('registered_address') is-invalid @enderror"
                   value="{{ old('registered_address', $farmer->registered_address ?? '') }}">
            @error('registered_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Taxpayer ID</label>
                <input type="text" name="taxpayer_id" class="form-control @error('taxpayer_id') is-invalid @enderror"
                       value="{{ old('taxpayer_id', $farmer->taxpayer_id ?? '') }}">
                @error('taxpayer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Seller Type</label>
                <select name="seller_type" class="form-select @error('seller_type') is-invalid @enderror">
                    <option value="" {{ old('seller_type', $farmer->seller_type ?? '')===''?'selected':'' }}>Select…</option>
                    @foreach(['individual','partnership','corporation'] as $t)
                        <option value="{{ $t }}" {{ old('seller_type', $farmer->seller_type ?? '')===$t?'selected':'' }}>
                            {{ ucfirst($t) }}
                        </option>
                    @endforeach
                </select>
                @error('seller_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <label class="form-label">Business Registration Certificate (PDF/JPG/PNG)</label>
                <input type="file" name="business_registration_certificate" class="form-control @error('business_registration_certificate') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                @error('business_registration_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Proof of Identity (PDF/JPG/PNG)</label>
                <input type="file" name="proof_of_identity" class="form-control @error('proof_of_identity') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                @error('proof_of_identity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('farmer.shopinfo') }}" class="btn btn-outline-secondary">Back</a>
            <button class="btn btn-success" type="submit">Submit for Review</button>
        </div>
    </form>
</div>
@endsection
