{{--views/farmers/products/edit.blade.php--}}
@extends('layouts.app')
@section('title','Edit Product')

@section('content')
<h4 class="mb-3">Edit Product</h4>
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('farmer.products.update', $product->productid) }}">
          @csrf @method('PATCH')
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" required value="{{ $product->name }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <input name="category" class="form-control" value="{{ $product->category }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Price (PHP)</label>
            <input name="price" type="number" min="0" step="0.01" class="form-control" value="{{ (float)$product->price }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input name="stock_quantity" type="number" min="0" class="form-control" value="{{ (int)$product->stockquantity }}" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Image URL</label>
            <input name="image_url" type="url" class="form-control" value="{{ $product->imageurl }}">
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-primary">Update</button>
            <a class="btn btn-outline-secondary" href="{{ route('farmer.products.index') }}">Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="card">
      <div class="card-body">
        <img src="{{ $product->imageurl ?: 'https://placehold.co/480x300?text=Preview' }}" class="img-fluid rounded" alt="">
      </div>
    </div>
  </div>
</div>
@endsection
