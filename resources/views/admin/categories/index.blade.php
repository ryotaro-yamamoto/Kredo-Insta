@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
  <form action="{{ route('admin.categories.store') }}" method="post">
    <div class="row mb-3">
      @csrf
      <div class="col-10">
        <input type="text" name="name" class="form-control" placeholder="Add a category">
      </div>
      <div class="col-2">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-plus"></i> Add
        </button>
      </div>
    </div>
  </form>

  <table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-warning text-secondary text-center">
      <tr>
        <th>#</th>
        <th>NAME</th>
        <th>COUNT</th>
        <th>LAST UPDATED</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($all_categories as $category)
        <tr class="text-center">
          <td>{{ $category->id }}</td>
          <td>{{ $category->name }}</td>
          <td>
            @if ($category->posts_count > 0)
              <a href="{{ route('admin.categories.posts', $category->id) }}" class="text-decoration-none text-primary">
                {{ $category->posts_count }}
              </a>
            @else
              {{$category->posts_count}}
            @endif
          </td>
          <td>{{ $category->updated_at }}</td>
          <td>
            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-category-{{$category->id}}">
              <i class="fa-solid fa-pen"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#destroy-category-{{$category->id}}">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </td>
        </tr>
        @include('admin.categories.modals.edit')
        @include('admin.categories.modals.destroy')
      @endforeach
      <tr class="text-center">
        <td></td>
        <td>Uncategorized <p class="text-secondary small m-0">Hidden posts are not included.</p></td>
        <td>{{$uncategorized_count}}</td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
@endsection
