@extends('layouts.app')

@section('title', 'Admin: Interests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">Interest List</h2>
  <a href="{{ route('admin.interests.create') }}" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Add Interest
  </a>
</div>

@if ($interests->isNotEmpty())
  <table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-primary text-secondary">
      <tr>
        <th>NAME</th>
        <th>OWNER</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($interests as $interest)
        <tr>
          <td class="fw-bold text-dark">{{ $interest->name }}</td>
          <td>
            <span class="text-muted">{{ $interest->users->count() }} 人</span>
          </td>
          <td>
            <form action="{{ route('admin.interests.destroy', $interest->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this interest?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="alert alert-info text-center">
    <i class="fa-solid fa-circle-info"></i> No interests found.
  </div>
@endif
@endsection
