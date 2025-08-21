{{-- resources/views/customer/orders/show.blade.php --}}
@extends('layouts.app')
@section('title','Order #'.$order->orderid)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Order #{{ $order->orderid }}</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-secondary">Back to Orders</a>
    @if($order->status === 'pending')
      <form action="{{ route('customer.orders.cancel', $order->orderid) }}" method="POST"
            onsubmit="return confirm('Cancel this order?');">
        @csrf
        <button class="btn btn-sm btn-outline-danger">Cancel Order</button>
      </form>
    @endif
  </div>
</div>

@php
  $badge = match($order->status) {
    'delivered' => 'text-bg-success',
    'shipped'   => 'text-bg-primary',
    'cancelled' => 'text-bg-secondary',
    default     => 'text-bg-warning text-dark'
  };
@endphp

<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <div class="small text-muted">Placed on</div>
          <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($order->orderdate)->format('M d, Y h:i A') }}</div>
        </div>
        <span class="badge {{ $badge }} fs-6">{{ ucfirst($order->status) }}</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Farmer</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($items as $it)
                @php $sub = (float)$it->price * (int)$it->quantity; @endphp
                <tr>
                  <td>{{ $it->product_name }}</td>
                  <td>{{ $it->farmer_name ?: '—' }}</td>
                  <td class="text-end">{{ (int)$it->quantity }}</td>
                  <td class="text-end">₱{{ number_format((float)$it->price,2) }}</td>
                  <td class="text-end">₱{{ number_format($sub,2) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Total</th>
                <th class="text-end">₱{{ number_format((float)$order->totalamount,2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Order summary / next steps --}}
  <div class="col-12 col-lg-4">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">What’s next?</h6></div>
      <div class="card-body">
        @if($order->status === 'pending')
          <p class="mb-2">Your order is pending. You’ll get a notification when the farmer ships it.</p>
        @elseif($order->status === 'shipped')
          <p class="mb-2">Your order is on the way to the Sync Point.</p>
        @elseif($order->status === 'delivered')
          <p class="mb-2">Order delivered. Enjoy your fresh produce!</p>
        @elseif($order->status === 'cancelled')
          <p class="mb-2">This order has been cancelled.</p>
        @endif
        <a href="{{ route('customer.notifications.index') }}" class="btn btn-sm btn-outline-primary">
          <i class="fa fa-bell me-1"></i> View Notifications
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
