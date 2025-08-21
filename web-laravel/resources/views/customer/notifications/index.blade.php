{{-- resources/views/customer/notifications/index.blade.php --}}
@extends('layouts.app')
@section('title','Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Notifications</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('customer.products.index') }}" class="btn btn-sm btn-outline-secondary">Marketplace</a>

    @if(!($tableMissing ?? false) && ($notifications->total() ?? 0) > 0)
      <form method="POST" action="{{ route('customer.notifications.clear') }}">
        @csrf
        <button class="btn btn-sm btn-outline-primary">Mark all as read</button>
      </form>
    @endif
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(($tableMissing ?? false))
  <div class="card">
    <div class="card-body text-muted">
      <div class="mb-1 fw-semibold">No notification table configured.</div>
      <div>Add the simple <code>notification</code> table to enable in-app alerts (see controller notes).</div>
    </div>
  </div>
@else
  @if(($notifications->count() ?? 0) === 0)
    <div class="card">
      <div class="card-body text-center py-5 text-muted">
        <div class="fs-5 mb-2">No notifications yet</div>
        <div>We’ll place order updates and messages here.</div>
      </div>
    </div>
  @else
    <div class="list-group">
      @foreach($notifications as $n)
        @php
          $isRead = (int)($n->is_read ?? 0) === 1;
          $pill = match(($n->type ?? 'order')) {
            'message' => 'text-bg-info',
            'system'  => 'text-bg-secondary',
            default   => 'text-bg-success',
          };
        @endphp
        <div class="list-group-item d-flex align-items-start gap-3 {{ $isRead ? 'opacity-75' : '' }}">
          <div>
            <span class="badge {{ $pill }}">{{ ucfirst($n->type ?? 'order') }}</span>
          </div>
          <div class="flex-grow-1">
            <div class="fw-semibold">{{ $n->title }}</div>
            @if($n->body)
              <div class="small text-muted">{{ $n->body }}</div>
            @endif
            <div class="small text-muted mt-1">{{ \Illuminate\Support\Carbon::parse($n->created_at)->diffForHumans() }}</div>
          </div>
          <div class="ms-auto">
            @if(!$isRead)
              <form method="POST" action="{{ route('customer.notifications.read', $n->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary">Mark read</button>
              </form>
            @else
              <span class="badge text-bg-light">Read</span>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $notifications->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  @endif
@endif
@endsection
