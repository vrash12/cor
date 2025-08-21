{{-- resources/views/customer/settings/index.blade.php --}}
@extends('layouts.app')
@section('title','My Profile')

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Profile & Settings</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('customer.settings.update') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input name="name" class="form-control" required value="{{ old('name', $user->name) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="col-12">
              <label class="form-label">Address</label>
              <input name="address" class="form-control" value="{{ old('address', $user->address) }}">
              <div class="form-text">Tarlac City address helps with nearest Sync Point suggestions (later feature).</div>
            </div>
          </div>

          <hr class="my-4">

          <h6 class="mb-3">Change Password (optional)</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" minlength="6">
            </div>
          </div>

          <hr class="my-4">

          <h6 class="mb-3">Notification Preferences</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email"
                       value="1" {{ ($prefs['notify_email'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="notify_email">
                  Email Updates
                </label>
              </div>
              <div class="form-text">Requires email delivery later.</div>
            </div>
            <div class="col-md-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notify_sms" name="notify_sms"
                       value="1" {{ ($prefs['notify_sms'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="notify_sms">
                  SMS Alerts
                </label>
              </div>
              <div class="form-text">Carrier SMS integration not yet enabled.</div>
            </div>
            <div class="col-md-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notify_inapp" name="notify_inapp"
                       value="1" {{ ($prefs['notify_inapp'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="notify_inapp">
                  In-App Notifications
                </label>
              </div>
              <div class="form-text">Shown in your Notifications tab.</div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button class="btn btn-success">Save Changes</button>
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary">Back to Marketplace</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Small summary card --}}
  <div class="col-12 col-lg-4">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Account</h6></div>
      <div class="card-body">
        <div class="mb-2"><span class="text-muted small d-block">Name</span>{{ $user->name }}</div>
        <div class="mb-2"><span class="text-muted small d-block">Email</span>{{ $user->email }}</div>
        <div class="mb-2"><span class="text-muted small d-block">Phone</span>{{ $user->phone ?: '—' }}</div>
        <div><span class="text-muted small d-block">Address</span>{{ $user->address ?: '—' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
