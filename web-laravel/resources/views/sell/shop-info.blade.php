@extends('layouts.app')
@section('title','Sell — Shop Info')

@section('content')
<h4 class="mb-3">Sell — Shop Info</h4>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('sell.shop.submit') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Farm / Shop Name <span class="text-danger">*</span></label>
        <input name="farmname" class="form-control" required
               value="{{ old('farmname', $farmer->farmname ?? '') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Certification</label>
        <input name="certification" class="form-control"
               value="{{ old('certification', $farmer->certification ?? '') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">About Your Farm / Shop</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $farmer->description ?? '') }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-success">Save & Continue</button>
      </div>
    </form>
  </div>
</div>
@endsection
