@extends('layouts.app')
@section('title','Sell — Business Info')

@section('content')
<h4 class="mb-3">Sell — Business Info</h4>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('sell.business.submit') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Cooperative (optional)</label>
        <select name="cooperativeid" class="form-select">
          <option value="">— Select —</option>
          @foreach($coops as $c)
            <option value="{{ $c->cooperativeid }}"
              @selected(old('cooperativeid', $farmer->cooperativeid ?? null) == $c->cooperativeid)>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Business Permit No. (optional)</label>
        <input name="businesspermit" class="form-control"
               value="{{ old('businesspermit', $farmer->businesspermit ?? '') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">TIN (optional)</label>
        <input name="tin" class="form-control" value="{{ old('tin', $farmer->tin ?? '') }}">
      </div>

      <p class="text-muted small">
        After submitting, your application will be reviewed by a Cooperative Admin. You’ll receive access to selling tools once approved.
      </p>

      <div class="d-flex gap-2">
        <a href="{{ route('sell.shop') }}" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-success">Submit Application</button>
      </div>
    </form>
  </div>
</div>
@endsection
