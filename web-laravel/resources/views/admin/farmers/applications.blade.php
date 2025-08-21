@extends('layouts.app')
@section('title', 'Farmer Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Farmer Applications</h4>
  <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back to Dashboard</a>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-body">
    @if ($apps->isEmpty())
      <div class="text-center py-5 text-muted">
        <div class="mb-2 fs-5">No pending farmer applications.</div>
        <div>Check back later.</div>
      </div>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 70px;">ID</th>
              <th>Applicant</th>
              <th>Farm Details</th>
              <th>Contact</th>
              <th>Cooperative</th>
              <th style="width: 220px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($apps as $app)
              <tr>
                <td class="fw-semibold">#{{ $app->farmerid }}</td>
                <td>
                  <div class="fw-semibold">{{ $app->user_name }}</div>
                  <div class="small text-muted">{{ $app->address }}</div>
                  <span class="badge bg-warning text-dark mt-1">Pending</span>
                </td>

                <td>
                  <div class="fw-semibold">
                    {{ $app->farmname ?: '—' }}
                    @if($app->certification)
                      <span class="badge bg-success ms-2">Certified</span>
                    @endif
                  </div>
                  @if($app->certification)
                    <div class="small text-muted">Cert: {{ $app->certification }}</div>
                  @endif
                  @if($app->description)
                    <div class="small text-muted mt-1">
                      {{ \Illuminate\Support\Str::limit($app->description, 90) }}
                    </div>
                  @endif
                </td>

                <td>
                  <div class="small"><i class="bi bi-envelope"></i> {{ $app->email }}</div>
                  <div class="small"><i class="bi bi-telephone"></i> {{ $app->phone }}</div>
                </td>

                <td>{{ $app->coop_name ?: '—' }}</td>

                <td>
                  <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewApp{{ $app->farmerid }}">
                      View
                    </button>

                    <form action="{{ route('admin.farmers.approve', $app->farmerid) }}" method="post"
                          onsubmit="return confirm('Approve Farmer #{{ $app->farmerid }} ({{ $app->user_name }})?');">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>

                    <form action="{{ route('admin.farmers.reject', $app->farmerid) }}" method="post"
                          onsubmit="return confirm('Reject Farmer #{{ $app->farmerid }} ({{ $app->user_name }})?');">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                    </form>
                  </div>
                </td>
              </tr>

              {{-- PUSH MODAL OUTSIDE THE TABLE --}}
              @push('modals')
              <div class="modal fade" id="viewApp{{ $app->farmerid }}" tabindex="-1"
                   aria-labelledby="viewAppLabel{{ $app->farmerid }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="viewAppLabel{{ $app->farmerid }}">
                        Farmer Application #{{ $app->farmerid }}
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="text-muted small">Applicant</div>
                        <div class="fw-semibold">{{ $app->user_name }}</div>
                        <div class="small">{{ $app->email }} • {{ $app->phone }}</div>
                        <div class="small">{{ $app->address }}</div>
                      </div>
                      <hr>
                      <div class="mb-3">
                        <div class="text-muted small">Farm</div>
                        <div class="fw-semibold">{{ $app->farmname ?: '—' }}</div>
                        <div class="small">Certification: {{ $app->certification ?: '—' }}</div>
                        <div class="small">Cooperative: {{ $app->coop_name ?: '—' }}</div>
                      </div>
                      <div>
                        <div class="text-muted small mb-1">Description</div>
                        <div class="small">{{ $app->description ?: '—' }}</div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <form action="{{ route('admin.farmers.reject', $app->farmerid) }}" method="post"
                            onsubmit="return confirm('Reject Farmer #{{ $app->farmerid }}?');" class="me-auto">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Reject</button>
                      </form>
                      <form action="{{ route('admin.farmers.approve', $app->farmerid) }}" method="post"
                            onsubmit="return confirm('Approve Farmer #{{ $app->farmerid }}?');">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              @endpush
              {{-- /PUSH MODAL --}}
            @endforeach
          </tbody>
        </table>
      </div>
      <p class="text-muted small mb-0">Showing {{ $apps->count() }} pending application(s).</p>
    @endif
  </div>
</div>
@endsection
