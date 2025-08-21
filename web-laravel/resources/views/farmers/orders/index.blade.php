@extends('layouts.app')
@section('title','Orders')

@section('content')
<h4 class="mb-3">Orders</h4>
<div class="card">
  <div class="card-body">
    @if(($orders ?? collect())->isEmpty())
      <p class="text-muted mb-0">No orders yet.</p>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>#</th><th>Date</th><th>Status</th><th>Items</th><th>Total</th><th>Customer</th><th class="text-end">Update</th>
            </tr>
          </thead>
          <tbody>
          @foreach($orders as $o)
            <tr>
              <td>{{ $o->orderid }}</td>
              <td>{{ \Illuminate\Support\Carbon::parse($o->orderdate)->format('Y-m-d H:i') }}</td>
              <td>
                @php $s = strtolower($o->status); @endphp
                <span class="badge
                  {{ $s==='pending'?'text-bg-secondary':'' }}
                  {{ $s==='shipped'?'text-bg-info':'' }}
                  {{ $s==='delivered'?'text-bg-success':'' }}
                  {{ $s==='cancelled'?'text-bg-danger':'' }}">
                  {{ ucfirst($s) }}
                </span>
              </td>
              <td>{{ (int)$o->items_count }}</td>
              <td>₱{{ number_format((float)$o->items_total,2) }}</td>
              <td>
                {{ $o->customer_name ?? '—' }}<br>
                <span class="text-muted small">{{ $o->customer_email ?? '' }}</span>
              </td>
              <td class="text-end">
                <div class="btn-group">
                  <form method="POST" action="{{ route('farmer.orders.confirm', $o->orderid) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="shipped">
                    <button class="btn btn-sm btn-outline-primary" @disabled(strtolower($o->status)!=='pending')>Mark Shipped</button>
                  </form>
                  <form method="POST" action="{{ route('farmer.orders.confirm', $o->orderid) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="delivered">
                    <button class="btn btn-sm btn-outline-success" @disabled(!in_array(strtolower($o->status),['pending','shipped']))>Delivered</button>
                  </form>
                  <form method="POST" action="{{ route('farmer.orders.confirm', $o->orderid) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button class="btn btn-sm btn-outline-danger" @disabled(strtolower($o->status)==='delivered')>Cancel</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
