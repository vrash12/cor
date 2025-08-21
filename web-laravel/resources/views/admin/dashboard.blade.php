@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Overview</h5>
        <div class="row row-cols-2 row-cols-md-4 g-3 mt-1">
          <div class="col">
            <div class="p-3 border rounded text-center bg-light">
              <div class="fw-semibold">Farmers</div>
              <div class="display-6">{{ $stats['farmers'] ?? '—' }}</div>
            </div>
          </div>
          <div class="col">
            <div class="p-3 border rounded text-center bg-light">
              <div class="fw-semibold">Customers</div>
              <div class="display-6">{{ $stats['customers'] ?? '—' }}</div>
            </div>
          </div>
          <div class="col">
            <div class="p-3 border rounded text-center bg-light">
              <div class="fw-semibold">Products</div>
              <div class="display-6">{{ $stats['products'] ?? '—' }}</div>
            </div>
          </div>
          <div class="col">
            <div class="p-3 border rounded text-center bg-light">
              <div class="fw-semibold">Orders</div>
              <div class="display-6">{{ $stats['orders'] ?? '—' }}</div>
            </div>
          </div>
        </div>

        <hr class="my-4">

        <h6>Quick Actions</h6>
        <div class="d-flex flex-wrap gap-2">
          <a href="#" class="btn btn-sm btn-outline-success disabled">Manage Sync Points</a>
          <a href="#" class="btn btn-sm btn-outline-success disabled">Cooperatives</a>
          <a href="#" class="btn btn-sm btn-outline-success disabled">Verify Farmers</a>
          <a href="#" class="btn btn-sm btn-outline-success disabled">Orders</a>
        </div>
        <p class="text-muted small mt-2 mb-0">Hook these buttons to your admin routes as you build them.</p>
      </div>
    </div>
    <a href="{{ route('admin.farmers.applications') }}" class="btn btn-sm btn-outline-success">Verify Farmers</a>

  </div>

  <div class="col-12 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Notes</h6>
        <ul class="mb-0 text-muted small">
          <li>Use role <code>CooperativeAdmin</code> to access this dashboard.</li>
          <li>Seed Tarlac barangays & sync points to enable nearest pickup suggestions.</li>
          <li>Add charts later (orders by status, inventory low-stock alerts).</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
