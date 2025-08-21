{{-- resources/views/customer/orders/index.blade.php --}}
@extends('layouts.app')
@section('title', 'My Orders - Farm2Go')

@push('styles')
<style>
    .orders-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(25, 135, 84, 0.1);
        position: relative;
        overflow: hidden;
    }

    .orders-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(25, 135, 84, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .orders-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .orders-table {
        margin-bottom: 0;
    }

    .orders-table thead {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
    }

    .orders-table thead th {
        border: none;
        color: var(--dark-green);
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1.25rem 1rem;
    }

    .orders-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        vertical-align: middle;
    }

    .orders-table tbody tr {
        transition: all 0.3s ease;
    }

    .orders-table tbody tr:hover {
        background: rgba(25, 135, 84, 0.02);
        transform: translateY(-1px);
    }

    .order-id {
        font-weight: 700;
        color: var(--dark-green);
        font-size: 0.95rem;
    }

    .order-date {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .order-total {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .status-badge.status-delivered {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .status-badge.status-shipped {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .status-badge.status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .status-badge.status-cancelled {
        background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn-view {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1.5px solid rgba(25, 135, 84, 0.2);
        color: var(--primary-green);
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(32, 201, 151, 0.15) 100%);
        border-color: var(--primary-green);
        color: var(--dark-green);
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(248, 113, 113, 0.1) 100%);
        border: 1.5px solid rgba(239, 68, 68, 0.2);
        color: #dc2626;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(248, 113, 113, 0.15) 100%);
        border-color: #dc2626;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #e2e8f0;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h3 {
        color: #4a5568;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #718096;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-marketplace {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.3);
    }

    .btn-marketplace:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(25, 135, 84, 0.4);
        background: linear-gradient(135deg, #146c43 0%, #1a9e7a 100%);
        color: white;
    }

    .orders-summary {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .summary-item {
        text-align: center;
        padding: 1rem;
    }

    .summary-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-green);
        display: block;
    }

    .summary-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.5rem;
    }

    .table-responsive {
        border-radius: 16px;
        overflow: hidden;
    }

    .no-orders-illustration {
        width: 300px;
        height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
        overflow: hidden;
    }

    .no-orders-illustration::before {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(25, 135, 84, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }

    .page-header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-outline-marketplace {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-marketplace:hover {
        background: var(--primary-green);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }

    @media (max-width: 768px) {
        .orders-header {
            padding: 1.5rem;
            text-align: center;
        }
        
        .page-header-actions {
            justify-content: center;
            width: 100%;
        }
        
        .orders-table thead th {
            padding: 1rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .orders-table tbody td {
            padding: 1rem 0.5rem;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-view, .btn-cancel {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }
        
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .no-orders-illustration {
            width: 250px;
            height: 150px;
        }
    }

    /* Custom table responsive scroll */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(25, 135, 84, 0.3);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: rgba(25, 135, 84, 0.5);
    }
</style>
@endpush

@section('content')
<!-- Enhanced Header Section -->
<div class="orders-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold text-dark mb-2">
                <i class="fas fa-receipt text-success me-3"></i>
                Order History
            </h1>
            <p class="lead text-muted mb-0">
                Track and manage all your farm-fresh orders in one place
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="page-header-actions">
                <a href="{{ route('customer.products.index') }}" class="btn-outline-marketplace">
                    <i class="fas fa-store-alt"></i>
                    Browse Marketplace
                </a>
            </div>
        </div>
    </div>
</div>

@if(($orders ?? collect())->isEmpty())
    <!-- Empty State -->
    <div class="orders-card">
        <div class="card-body">
            <div class="empty-state">
                <div class="no-orders-illustration">
                    <div class="empty-state-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start exploring our fresh marketplace to discover amazing products from local farmers!</p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('customer.products.index') }}" class="btn-marketplace">
                        <i class="fas fa-seedling"></i>
                        Explore Marketplace
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Orders Summary (Optional) -->
    @php
        $totalOrders = $orders->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $totalSpent = $orders->sum('totalamount');
    @endphp
    
    <div class="orders-summary">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="summary-number">{{ $totalOrders }}</span>
                    <div class="summary-label">Total Orders</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="summary-number">{{ $pendingOrders }}</span>
                    <div class="summary-label">Pending</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="summary-number">{{ $deliveredOrders }}</span>
                    <div class="summary-label">Delivered</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="summary-number">₱{{ number_format($totalSpent, 0) }}</span>
                    <div class="summary-label">Total Spent</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-card">
        <div class="table-responsive">
            <table class="table orders-table align-middle">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>Order ID</th>
                        <th><i class="fas fa-calendar me-2"></i>Date</th>
                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                        <th class="text-end"><i class="fas fa-peso-sign me-2"></i>Total</th>
                        <th class="text-end"><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                        @php
                            $statusClass = match($o->status) {
                                'delivered' => 'status-delivered',
                                'shipped'   => 'status-shipped',
                                'cancelled' => 'status-cancelled',
                                default     => 'status-pending'
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="order-id">#{{ $o->orderid }}</div>
                            </td>
                            <td>
                                <div class="order-date">
                                    {{ \Illuminate\Support\Carbon::parse($o->orderdate)->format('M d, Y') }}
                                </div>
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Carbon::parse($o->orderdate)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fas fa-{{ match($o->status) {
                                        'delivered' => 'check-circle',
                                        'shipped' => 'truck',
                                        'cancelled' => 'times-circle',
                                        default => 'clock'
                                    } }} me-1"></i>
                                    {{ ucfirst($o->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="order-total">₱{{ number_format((float)$o->totalamount, 2) }}</div>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons">
                                    <a href="{{ route('customer.orders.show', $o->orderid) }}" class="btn-view">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    
                                    @if($o->status === 'pending')
                                        <form action="{{ route('customer.orders.cancel', $o->orderid) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to cancel Order #{{ $o->orderid }}? This action cannot be undone.');">
                                            @csrf
                                            <button type="submit" class="btn btn-cancel">
                                                <i class="fas fa-times"></i>
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for cancel actions
    const cancelForms = document.querySelectorAll('form[action*="cancel"]');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const orderNumber = this.action.split('/').pop();
            if (!confirm(`Are you sure you want to cancel Order #${orderNumber}? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });
    });

    // Add loading states for action buttons
    const actionButtons = document.querySelectorAll('.btn-view, .btn-cancel');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.type === 'submit') {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
                this.disabled = true;
                
                // Re-enable after timeout as fallback
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });

    // Add smooth hover effects for table rows
    const tableRows = document.querySelectorAll('.orders-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endpush