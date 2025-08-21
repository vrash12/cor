{{views/farmers/products/lowstock.blade.php}}
@extends('layouts.app')
@section('title','Low Stock')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Low Stock Products</h4>
  <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('farmer.products.lowstock') }}">
    <label class="form-label mb-0">Threshold</label>
    <input type="number" name="threshold" class="form-control" style="width:110px" value="{{ $threshold }}">
    <button class="btn btn-outline-secondary">Apply</button>
  </form>
</div>

<div class="card">
  <div class="card-body">
    @if(($products ?? collect())->isEmpty())
      <p class="text-muted mb-0">No items at or below {{ $threshold }}.</p>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Product</th><th>Stock</th><th>Adjust</th></tr></thead>
          <tbody>
            @foreach($products as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td><span class="badge text-bg-danger">{{ (int)$p->stockquantity }}</span></td>
                <td>
                  <form action="{{ route('farmer.inventory.adjust', $p->productid) }}" method="POST" class="d-flex gap-2">
                    @csrf @method('PATCH')
                    <input name="delta" type="number" class="form-control" style="max-width:140px" placeholder="+10">
                    <button class="btn btn-sm btn-outline-secondary">Update</button>
                  </form>
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
