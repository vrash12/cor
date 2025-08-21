{{-- resources/views/customer/products/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Marketplace - Farm2Go')

@push('styles')
<style>
    .marketplace-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(25, 135, 84, 0.1);
        position: relative;
        overflow: hidden;
    }

    .marketplace-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(25, 135, 84, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .search-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-input {
        border: 2px solid rgba(25, 135, 84, 0.1);
        border-radius: 12px;
        transition: all 0.3s ease;
        background: rgba(248, 249, 250, 0.8);
    }

    .search-input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        background: white;
    }

    .filter-select {
        border: 2px solid rgba(25, 135, 84, 0.1);
        border-radius: 12px;
        transition: all 0.3s ease;
        background: rgba(248, 249, 250, 0.8);
    }

    .filter-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        background: white;
    }

    .filter-chip {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
        color: var(--dark-green);
        border: 1px solid rgba(25, 135, 84, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.25rem;
        transition: all 0.3s ease;
    }

    .filter-chip:hover {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(32, 201, 151, 0.15) 100%);
        transform: translateY(-1px);
    }

    .filter-chip .remove-filter {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 600;
        margin-left: 0.5rem;
        opacity: 0.8;
    }

    .filter-chip .remove-filter:hover {
        opacity: 1;
        color: var(--dark-green);
    }

    .product-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .product-card .card-body {
        padding: 1.5rem;
        position: relative;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .product-category {
        color: var(--primary-green);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--dark-green);
        margin: 0.75rem 0;
    }

    .product-description {
        color: #718096;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }

    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stock-badge.in-stock {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
    }

    .stock-badge.out-of-stock {
        background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
        color: white;
    }

    .farmer-link {
        color: #718096;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .farmer-link:hover {
        color: var(--primary-green);
        border-color: var(--primary-green);
        background: rgba(25, 135, 84, 0.05);
    }

    .order-btn {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }

    .order-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4);
        background: linear-gradient(135deg, #146c43 0%, #1a9e7a 100%);
    }

    .order-btn:disabled {
        background: #9ca3af;
        box-shadow: none;
        cursor: not-allowed;
    }

    .results-count {
        background: rgba(25, 135, 84, 0.05);
        color: var(--dark-green);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        margin-bottom: 2rem;
        border: 1px solid rgba(25, 135, 84, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .quick-order-modal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .quick-order-modal .modal-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
        border-bottom: 1px solid rgba(25, 135, 84, 0.1);
        border-radius: 20px 20px 0 0;
    }

    .quick-order-modal .product-image {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .quick-order-modal .form-control {
        border: 2px solid rgba(25, 135, 84, 0.1);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .quick-order-modal .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    /* Enhanced pagination */
    .pagination .page-link {
        border: none;
        color: var(--primary-green);
        font-weight: 500;
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: rgba(25, 135, 84, 0.1);
        color: var(--dark-green);
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }

    @media (max-width: 768px) {
        .marketplace-header {
            padding: 1.5rem;
            text-align: center;
        }
        
        .search-section {
            padding: 1rem;
        }
        
        .product-card .card-img-top {
            height: 160px;
        }
        
        .filter-chip {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>
@endpush

@section('content')
@php use Illuminate\Support\Str; @endphp

<!-- Enhanced Header Section -->
<div class="marketplace-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold text-dark mb-2">
                <i class="fas fa-store-alt text-success me-3"></i>
                Fresh Marketplace
            </h1>
            <p class="lead text-muted mb-0">
                Discover fresh, locally grown produce directly from our trusted farmers
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                @if(($q ?? '') !== '' || ($category ?? '') !== '')
                    <a href="{{ route('customer.products.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-times me-2"></i>Clear All Filters
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Search Section -->
<div class="search-section">
    <form method="GET" action="{{ route('customer.products.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label fw-semibold text-dark">
                    <i class="fas fa-search me-1"></i>Search Products
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input
                        type="search"
                        name="q"
                        class="form-control search-input border-start-0 ps-0"
                        placeholder="Search by product name or farmer..."
                        value="{{ $q ?? '' }}">
                </div>
            </div>

            <div class="col-lg-3">
                <label class="form-label fw-semibold text-dark">
                    <i class="fas fa-tags me-1"></i>Category
                </label>
                <select name="category" class="form-select filter-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2">
                <button type="submit" class="btn btn-success w-100 fw-semibold">
                    <i class="fas fa-filter me-2"></i>Apply
                </button>
            </div>
        </div>
    </form>

    <!-- Active Filter Chips -->
    @if(($q ?? '') !== '' || ($category ?? '') !== '')
        <div class="mt-3">
            <div class="d-flex flex-wrap align-items-center">
                <span class="text-muted fw-semibold me-3 small">Active Filters:</span>
                @if(($q ?? '') !== '')
                    <div class="filter-chip">
                        <i class="fas fa-search"></i>
                        "{{ $q }}"
                        <a href="{{ route('customer.products.index', array_filter(['category' => $category ?? ''])) }}" 
                           class="remove-filter">×</a>
                    </div>
                @endif
                @if(($category ?? '') !== '')
                    <div class="filter-chip">
                        <i class="fas fa-tag"></i>
                        {{ $category }}
                        <a href="{{ route('customer.products.index', array_filter(['q' => $q ?? ''])) }}" 
                           class="remove-filter">×</a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Results Count -->
@if(!$products->isEmpty())
    <div class="results-count">
        <i class="fas fa-chart-bar me-2"></i>
        Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
    </div>
@endif

<!-- Products Grid or Empty State -->
@if($products->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No Products Found</h3>
                <p class="mb-4">We couldn't find any products matching your search criteria.</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ route('customer.products.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-refresh me-2"></i>Browse All Products
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($products as $p)
            @php
                $img = $p->imageurl ?: '';
                $img = $img && preg_match('#^https?://#i', $img) ? $img : ($img ? asset($img) : 'https://placehold.co/400x300/f8f9fa/6c757d?text=No+Image');
                $inStock = (int)($p->stockquantity ?? 0) > 0;
            @endphp
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 product-card position-relative">
                    <!-- Stock Badge -->
                    <div class="stock-badge {{ $inStock ? 'in-stock' : 'out-of-stock' }}">
                        {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                    </div>
                    
                    <!-- Product Image -->
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $img }}" class="card-img-top" alt="{{ $p->name }}">
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <div class="product-category">{{ $p->category ?: 'Uncategorized' }}</div>
                            <h5 class="product-title">{{ $p->name }}</h5>
                        </div>
                        
                        <div class="product-price">
                            ₱{{ number_format((float)$p->price, 2) }}
                        </div>
                        
                        <p class="product-description">
                            {{ Str::limit((string) $p->description, 100) }}
                        </p>
                        
                        <!-- Stock Info -->
                        @if($inStock)
                            <div class="small text-muted mb-3">
                                <i class="fas fa-box me-1"></i>
                                {{ (int)$p->stockquantity }} available
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ route('customer.farmers.show', $p->farmerid) }}" class="farmer-link">
                                    <i class="fas fa-user"></i>
                                    {{ Str::limit($p->farmer_name ?: 'Unknown Farmer', 15) }}
                                </a>
                            </div>
                            
                            <button
                                class="btn order-btn w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#quickOrder{{ $p->productid }}"
                                {{ $inStock ? '' : 'disabled' }}>
                                <i class="fas fa-cart-plus me-2"></i>
                                {{ $inStock ? 'Order Now' : 'Out of Stock' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Order Modal -->
            <div class="modal fade quick-order-modal" id="quickOrder{{ $p->productid }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('customer.order.place') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->productid }}">
                            
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">
                                    <i class="fas fa-shopping-cart text-success me-2"></i>
                                    Order {{ $p->name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <img src="{{ $img }}" alt="{{ $p->name }}" 
                                             class="product-image w-100" 
                                             style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="fw-bold text-dark">{{ $p->name }}</h6>
                                        <div class="product-price mb-2">₱{{ number_format((float)$p->price, 2) }}</div>
                                        <div class="small text-muted mb-3">
                                            <i class="fas fa-box me-1"></i>
                                            {{ (int)($p->stockquantity ?? 0) }} available
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Quantity</label>
                                            <input type="number" 
                                                   name="quantity" 
                                                   class="form-control" 
                                                   min="1" 
                                                   max="{{ (int)($p->stockquantity ?? 0) }}" 
                                                   value="1" 
                                                   {{ $inStock ? '' : 'disabled' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-success" {{ $inStock ? '' : 'disabled' }}>
                                    <i class="fas fa-check me-2"></i>Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Enhanced Pagination -->
    <div class="pagination-wrapper">
        {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection