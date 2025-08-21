@extends('layouts.app')
@section('title','Farmer Profile')

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title mb-1">{{ $farmer->user_name ?? 'Farmer' }}</h5>
        <div class="text-muted small mb-3">{{ $farmer->email ?? '' }}</div>
        <dl class="mb-0">
          <dt>Farm Name</dt><dd>{{ $farmer->farmname ?? '—' }}</dd>
          <dt>Certification</dt><dd>{{ $farmer->certification ?? '—' }}</dd>
          <dt>Address</dt><dd>{{ $farmer->address ?? '—' }}</dd>
          <dt>Phone</dt><dd>{{ $farmer->phone ?? '—' }}</dd>
          <dt>About</dt><dd class="mb-0">{{ $farmer->description ?? '—' }}</dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-8">
    <h5 class="mb-2">Products</h5>
    <div class="row g-3">
      @forelse($products as $p)
        <div class="col-12 col-md-6">
          <div class="card h-100">
            <img src="{{ $p->imageurl ?: 'https://placehold.co/640x360?text=Farm2Go' }}" class="card-img-top" alt="">
            <div class="card-body d-flex flex-column">
              <h6 class="card-title">{{ $p->name }}</h6>
              <p class="text-muted small flex-grow-1">
                {{ \Illuminate\Support\Str::limit($p->description ?? '', 120) }}
              </p>
              <div class="mb-2">
                <span class="badge text-bg-success">₱{{ number_format((float)$p->price,2) }}</span>
                <span class="badge text-bg-secondary">Stock: {{ (int)($p->stockquantity ?? 0) }}</span>
              </div>
              <form class="d-flex gap-2 align-items-center" method="POST" action="{{ route('customer.order.place') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $p->productid }}">
                <input type="number" name="quantity" class="form-control" min="1" max="{{ (int)($p->stockquantity ?? 1) }}" value="1" style="max-width:120px">
                <button class="btn btn-primary">Order</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12"><p class="text-muted">No products from this farmer.</p></div>
      @endforelse
    </div>
  </div>
</div>
@endsection
