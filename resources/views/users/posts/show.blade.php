@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
      .col-4 {
        overflow-y: scroll;
      }

      .card-body{
        position: absolute;
        top: 65px;
      }
    </style>
    <div class="row border shadow" style="height: 85vh;">
      <div class="col-8 p-0">
        @if ($post->images->isNotEmpty())
          <div id="carousel-{{ $post->id }}" class="carousel slide h-100" data-bs-ride="carousel">
            <div class="carousel-indicators">
              @foreach ($post->images as $index => $image)
                <button
                  type="button"
                  data-bs-target="#carousel-{{ $post->id }}"
                  data-bs-slide-to="{{ $index }}"
                  class="{{ $index === 0 ? 'active' : '' }}"
                  aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                  aria-label="Slide {{ $index + 1 }}"
                  style="background-color: gray; width: 10px; height: 10px; border-radius: 50%;"
                ></button>
              @endforeach
            </div>
            <div class="carousel-inner h-100">
              @foreach ($post->images->take(5) as $index => $image) {{-- 最大5枚まで --}}
                <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                  <div class="d-flex align-items-center justify-content-center h-100 bg-white border">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100 object-fit-contain" alt="Image {{ $index + 1 }}">
                  </div>
                </div>
              @endforeach
            </div>
            @if ($post->images->count() > 1)
              <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black;"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black;"></span>
                <span class="visually-hidden">Next</span>
              </button>
            @endif
          </div>
        @else
          <div class="d-flex align-items-center justify-content-center h-100 bg-black border">
            <img src="{{ asset('no-image.png') }}" class="d-block w-100 object-fit-contain" alt="no image">
          </div>
        @endif
      </div>
      <div class="col-4 px-0 bg-white">
        <div class="card border-0">
          <div class="card-header bg-white py-3">
            <div class="row align-items-center">
              <div class="col-auto">
                <a href="{{route('profile.show', $post->user->id)}}">
                  @if ($post->user->avatar)
                      <img src="{{$post->user->avatar}}" alt="{{$post->user->name}}" class="rounded-circle avatar-sm border border-secondary border-opacity-25">
                  @else
                      <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                  @endif
                </a>
              </div>
              <div class="col ps-0">
                <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
              </div>
              <div class="col-auto">
                {{-- IF you are the owner, you can edit or delete --}}
                @if (Auth::user()->id === $post->user->id)
                <div class="dropdown">
                  <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>

                  <div class="dropdown-menu">
                    <a href="{{route('post.edit', $post->id)}}" class="dropdown-item">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}">
                        <i class="fa-regular fa-trash-can"></i> Delete
                    </button>
                  </div>
                  {{-- Include modal here --}}
                  @include('users.posts.contents.modals.delete')
                </div>
                @else
                  {{-- If you are not the owner, show follow/unfollow button --}}
                  @if ($post->user->isFollowed())
                    <form action="{{route('follow.destroy', $post->user->id)}}" method="post" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                    </form>
                  @else
                    <form action="{{route('follow.store', $post->user->id)}}" method="post">
                      @csrf
                      <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                    </form>
                  @endif
                  {{-- show follow button for now --}}
                @endif
              </div>
            </div>
          </div>
          <div class="card-body w-100">
            {{-- heart button + number of likes + categories --}}
            <div class="row align-items-center">
              <div class="col-auto">
                @if ($post->isLiked())
                  <form action="{{route('like.destroy', $post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm p-0">
                      <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                  </form>
                @else
                  <form action="{{route('like.store', $post->id)}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0">
                      <i class="fa-regular fa-heart"></i>
                    </button>
                  </form>
                @endif
              </div>
              <div class="col-auto px-0">
                <span class="fw-bold fs-5">{{$post->likes->count()}}</span>
              </div>
              <div class="col text-end">
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
              </div>
            </div>

            {{-- owner + description --}}
            <a href="" class="text-decoration-none text-dark fw-bold">{{$post->user->name}}</a>
            &nbsp;
            <p class="d-inline fw-light">{{$post->description}}</p>
            <p class="text-uppercase text-muted xsmall">
              {{date('M d, Y', strtotime($post->created_at))}}
            </p>

            {{-- Comments --}}
            <div class="mt-4">
              <form action="{{route('comment.store', $post->id)}}" method="post">
                @csrf
                <div class="input-group">
                  <textarea name="comment_body{{$post->id}}" cols="30" rows="1" class="textarea form-control form-control-sm" placeholder="Add a comment...">{{old('comment_body'. $post->id)}}</textarea>
                  <button type="submit" class="btn btn-outline-secondary btn-sm" title="Post">
                    <i class="fa-regular fa-paper-plane text-primary"></i>
                  </button>
                </div>
                {{-- error --}}
                @error('comment_body' . $post->id)
                  <div class="text-danger small">{{ $message }}</div>
                @enderror
              </form>
              {{-- show all comments here --}}
              @if ($post->comments->isNotEmpty())
                <ul class="list-group mt-2">
                  @foreach ($post->comments as $comment)
                    <li class="list-group-item border-0 p-0 mb-2">
                      <a href="{{route('profile.show', $comment->user->id)}}" class="text-decoration-none text-dark fw-bold">{{$comment->user->name}}</a>
                      &nbsp;
                      <p class="d-inline fw-light">{{$comment->body}}</p>

                      <form action="{{route('comment.destroy', $comment->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <span class="text-uppercase text-muted xsmall">
                          {{date('M d, Y', strtotime($comment->created_at))}}
                        </span>

                        {{-- If the auth user is the owner of the comment, show a delete btn --}}
                        @if (Auth::user()->id === $comment->user->id)
                          &middot;
                          <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                        @endif
                      </form>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
