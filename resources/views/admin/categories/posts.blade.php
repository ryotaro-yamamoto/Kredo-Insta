@extends('layouts.app')

@section('title', 'Category Posts')

@section('content')
  <h4 class="mb-4">
    Posts in <span class="fw-bold text-primary">{{ $category->name }}</span>
  </h4>

  @if ($category->posts->isEmpty())
    <p class="text-muted">No posts in this category.</p>
  @else
    <div class="row row-cols-1 row-cols-md-2 g-4">
    @foreach ($category->posts as $post)
        <div class="col-md-6 col-lg-6">
            <div class="card shadow-sm h-100 mx-auto d-flex flex-column">
                <a href="{{ route('post.show', $post->id) }}" class="card-img-top-wrapper" style="height: 250px; overflow: hidden; display: block;">
                    <img src="{{ $post->image }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Post image">
                </a>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                    @if ($post->user->avatar)
                        <img src="{{ $post->user->avatar }}" class="rounded-circle avatar-sm me-2" alt="Avatar">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary icon-sm me-2"></i>
                    @endif
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-dark fw-bold text-decoration-none">
                        {{ $post->user->name }}
                    </a>
                    </div>

                    <p class="mb-1">{{ $post->description }}</p>

                    <div class="d-flex align-items-center mb-2 mt-auto">
                    @if ($post->isLiked())
                        <form action="{{route('like.destroy', $post->id)}}" method="post" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm p-0">
                            <i class="fa-solid fa-heart text-danger"></i>
                        </button>
                        </form>
                    @else
                        <form action="{{route('like.store', $post->id)}}" method="post" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-sm p-0">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        </form>
                    @endif
                    <span>{{ $post->likes->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
  @endif
@endsection
