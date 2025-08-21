@extends('layouts.app')
@section('title','My Products')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <h5 class="mb-0">My Products</h5>

    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('farmer.products.lowstock') }}">Low Stock</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('farmer.orders.index') }}">Orders</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('farmer.reports.sales') }}">Sales Report</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('farmer.profile.edit') }}">My Profile</a>

      {{-- Add Product --}}
      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa fa-plus me-1"></i> Add Product
      </button>
    </div>
  </div>

  <div class="card-body">
    @if(($products ?? collect())->isEmpty())
      <p class="text-muted mb-0">No products yet — click <strong>Add Product</strong> to create one.</p>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Item</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($products as $p)
            <tr>
              <td class="d-flex align-items-center gap-2">
                <img
                  src="{{ $p->imageurl ? asset($p->imageurl) : 'https://placehold.co/64x64?text=Img' }}"
                  width="48" height="48" class="rounded" alt="">
                <div>
                  <div class="fw-semibold">{{ $p->name }}</div>
                  <div class="text-muted small">{{ Str::limit($p->description, 60) }}</div>
                </div>
              </td>
              <td>{{ $p->category ?? '—' }}</td>
              <td>₱{{ number_format((float)$p->price,2) }}</td>
              <td>
                <span class="badge {{ (int)($p->stockquantity ?? 0) <= 10 ? 'text-bg-warning' : 'text-bg-secondary' }}">
                  {{ (int)($p->stockquantity ?? 0) }}
                </span>
              </td>
              <td class="text-end">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#editProduct{{ $p->productid }}">
                    Edit
                  </button>
                  <button class="btn btn-sm btn-outline-secondary"
                          data-bs-toggle="modal"
                          data-bs-target="#adjustStock{{ $p->productid }}">
                    Adjust
                  </button>
                  <button class="btn btn-sm btn-outline-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#deleteProduct{{ $p->productid }}">
                    Delete
                  </button>
                </div>
              </td>
            </tr>

            {{-- EDIT PRODUCT MODAL --}}
            @push('modals')
            <div class="modal fade" id="editProduct{{ $p->productid }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                  <form method="POST"
                        action="{{ route('farmer.products.update', $p->productid) }}"
                        enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Product — #{{ $p->productid }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-8">
                          <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" required
                                   value="{{ old('name', $p->name) }}">
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $p->description) }}</textarea>
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Category</label>
                            <input name="category" class="form-control"
                                   value="{{ old('category', $p->category) }}">
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Price (PHP)</label>
                            <input name="price" type="number" min="0" step="0.01" class="form-control" required
                                   value="{{ old('price', (float)$p->price) }}">
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Stock Quantity</label>
                            <input name="stock_quantity" type="number" min="0" class="form-control" required
                                   value="{{ old('stock_quantity', (int)$p->stockquantity) }}">
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Replace Image (optional)</label>
                            <input name="image" type="file" class="form-control" accept="image/*"
                                   data-preview="#preview-edit-{{ $p->productid }}">
                            <div class="form-text">JPG/PNG/GIF up to 2MB.</div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="border rounded p-2">
                            <div class="text-muted small mb-1">Preview</div>
                            <img id="preview-edit-{{ $p->productid }}"
                                 src="{{ $p->imageurl ? asset($p->imageurl) : 'https://placehold.co/480x300?text=Preview' }}"
                                 class="img-fluid rounded" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endpush

            {{-- ADJUST STOCK MODAL --}}
            @push('modals')
            <div class="modal fade" id="adjustStock{{ $p->productid }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST" action="{{ route('farmer.inventory.adjust', $p->productid) }}">
                    @csrf @method('PATCH')
                    <div class="modal-header">
                      <h5 class="modal-title">Adjust Stock — {{ $p->name }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-2">
                        <label class="form-label">Current Stock</label>
                        <input type="number" class="form-control" value="{{ (int)$p->stockquantity }}" readonly>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">Change By</label>
                        <div class="input-group">
                          <span class="input-group-text">±</span>
                          <input name="delta" type="number" class="form-control" placeholder="e.g., 10 or -2" required>
                        </div>
                        <div class="form-text">Use positive to restock, negative to deduct.</div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button class="btn btn-outline-primary">Apply</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endpush

            {{-- DELETE MODAL --}}
            @push('modals')
            <div class="modal fade" id="deleteProduct{{ $p->productid }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST" action="{{ route('farmer.products.delete', $p->productid) }}">
                    @csrf @method('DELETE')
                    <div class="modal-header">
                      <h5 class="modal-title text-danger">Delete Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p class="mb-1">Are you sure you want to delete <strong>{{ $p->name }}</strong>?</p>
                      <small class="text-muted">This action cannot be undone.</small>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button class="btn btn-danger">Delete</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endpush

          @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

{{-- ADD PRODUCT MODAL --}}
@push('modals')
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="POST"
            action="{{ route('farmer.products.create') }}"
            enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-8">
              <div class="mb-2">
                <label class="form-label">Name</label>
                <input name="name" class="form-control" required value="{{ old('name') }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
              </div>
              <div class="mb-2">
                <label class="form-label">Category</label>
                <input name="category" class="form-control" value="{{ old('category') }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Price (PHP)</label>
                <input name="price" type="number" min="0" step="0.01" class="form-control" required value="{{ old('price') }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Stock Quantity</label>
                <input name="stock_quantity" type="number" min="0" class="form-control" required value="{{ old('stock_quantity') }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Image</label>
                <input name="image" type="file" class="form-control" accept="image/*" data-preview="#preview-create">
                <div class="form-text">JPG/PNG/GIF up to 2MB.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="border rounded p-2">
                <div class="text-muted small mb-1">Preview</div>
                <img id="preview-create" src="https://placehold.co/480x300?text=Preview" class="img-fluid rounded" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success">Save Product</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

{{-- Tiny helper for live image previews --}}
@push('styles')
<style>
  .modal .form-text { font-size: .8rem; }
</style>
@endpush
<script>
  document.addEventListener('change', function (e) {
    const input = e.target;
    if (input.matches('input[type="file"][data-preview]') && input.files && input.files[0]) {
      const img = document.querySelector(input.getAttribute('data-preview'));
      if (img) { img.src = URL.createObjectURL(input.files[0]); }
    }
  });
</script>
@endsection
