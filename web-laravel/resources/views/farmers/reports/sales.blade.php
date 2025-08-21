@extends('layouts.app')
@section('title','Sales Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Sales Report</h4>
  <form class="d-flex gap-2" method="GET" action="{{ route('farmer.reports.sales') }}">
    <input type="date" name="from" class="form-control" value="{{ $from }}">
    <input type="date" name="to" class="form-control" value="{{ $to }}">
    <button class="btn btn-outline-secondary">Filter</button>
  </form>
</div>

<div class="row g-4">
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3">Daily Totals</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Date</th><th>Units</th><th>Revenue</th></tr></thead>
            <tbody>
              @forelse($daily as $d)
                <tr>
                  <td>{{ $d->day }}</td>
                  <td>{{ (int)$d->units }}</td>
                  <td>₱{{ number_format((float)$d->revenue,2) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-muted">No data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-3">Top Products</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Product</th><th>Units</th><th>Revenue</th></tr></thead>
            <tbody>
              @forelse($topProducts as $t)
                <tr>
                  <td>{{ $t->name }}</td>
                  <td>{{ (int)$t->units }}</td>
                  <td>₱{{ number_format((float)$t->revenue,2) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-muted">No data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
