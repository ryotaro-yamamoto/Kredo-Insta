@extends('layouts.app')

@section('title', 'Admin: Create Interest')

@section('content')
<div class="card shadow-sm bg-white p-4">
  <h2 class="mb-4">Create New Interest</h2>

  <form action="{{ route('admin.interests.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label fw-bold">Interest Name</label>
      <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="text-end">
      <a href="{{ route('admin.interests') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-check"></i> Create
      </button>
    </div>
  </form>
</div>
@endsection