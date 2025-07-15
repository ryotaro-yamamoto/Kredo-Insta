@extends('layouts.app')

@section('title', 'Admin: Search Posts')

@section('content')
@if(!($all_posts->isEmpty()))
  <table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-primary text-secondary">
      <tr>
        <th></th>
        <th></th>
        <th>CATEGORY</th>
        <th>OWNER</th>
        <th>CREATED AT</th>
        <th>STATUS</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($all_posts as $post)
        <tr>
          <td>{{$post->id}}</td>
          <td>
            @if ($post->image)
              <img src="{{ $post->image }}" alt="{{$post->id}}" class="d-block mx-auto image-md img-thumbnail">
            @else
              <i class="fa-solid fa-circle-user text-secondary icon-md d-block mx-auto"></i>
            @endif
          </td>
          <td>
            @if ($post->categoryPost->isEmpty())
            <div class="badge bg-dark">
              Uncategorized
            </div>
            @else
              @foreach ($post->categoryPost as $category_post)
                <div class="badge bg-secondary bg-opacity-50">
                  {{ $category_post->category->name }}
                </div>
              @endforeach
            @endif
          </td>
          <td>
            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">
              {{ $post->user->name }}
            </a>
          </td>
          <td>{{ $post->created_at }}</td>
          <td>
            @if ($post->trashed())
              <i class="fa-solid fa-circle-minus text-secondary"></i>&nbsp; Hide
            @else
              <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm" data-bs-toggle="dropdown">
                <i class="fa-solid fa-ellipsis"></i>
              </button>

              <div class="dropdown-menu">
                @if ($post->trashed())
                  <button class="dropdown-item text-secondary" data-bs-toggle="modal" data-bs-target="#visible-post-{{$post->id}}">
                    <i class="fa-solid fa-eye"></i> Unhide Post {{$post->id}}
                  </button>
                @else
                  <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#invisible-post-{{$post->id}}">
                    <i class="fa-solid fa-eye-slash"></i> Hide Post {{$post->id}}
                  </button>
                @endif
              </div>
            </div>
            {{-- include modal here --}}
            @include('admin.posts.modals.status')
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="d-flex justify-content-center">
    {{ $all_posts->links() }}
  </div>
@else
  <div class="alert alert-info text-center">
    <i class="fa-solid fa-circle-info"></i> No posts found.
  </div>
@endif
@endsection
