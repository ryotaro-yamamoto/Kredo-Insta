@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row gx-5">
    <div class="col-8">
        {{-- show stories modal --}}
        @include('users.stories.modal')

        {{-- Stories --}}
        @include('users.stories.story')

        {{-- Posts --}}
        @forelse ($home_posts as $post)
            <div class="card mb-4">
                {{-- title --}}
                @include('users.posts.contents.title')
                {{-- body --}}
                @include('users.posts.contents.body')
            </div>
        @empty
            @if (request()->has('categories'))
                <div class="text-center text-muted">
                    <h4>No Posts Found</h4>
                    <p>There are no posts in the selected category.</p>
                </div>
            @else
                <div class="text-center">
                    <h2>Share Photos</h2>
                    <p class="text-secondary">When you share photos, they'll appear on your profile.</p>
                    <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
                </div>
            @endif
        @endforelse
    </div>

    <div id="backToTopContainer" class="position-fixed start-0 bottom-0 m-4" style="z-index: 1000;">
        <button onclick="scrollToTop()" class="btn btn-outline-secondary fw-bold p-2 w-25">
             Back to Top ↑
    </button>
    </div>

    <div class="col-4">
        <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 py-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', Auth::user()->id) }}">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-md  border border-secondary border-opacity-25">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0">
                <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-decoration-none text-dark fw-bold">{{ Auth::user()->name }}</a>
                <p class="text-muted mb-0">{{Auth::user()->email}}</p>
            </div>
        </div>
        {{-- suggestions --}}{{-- New(RIKO) --}}
        @if ($suggested_users)
            <div class="row">
                <div class="col-auto">
                    <p class="fw-bold text-secondary fs-6">Suggestions For You</p>
                </div>
                <div class="col text-end">
                    <a href="{{ route('suggestions.index') }}" class="fw-bold text-dark text-decoration-none">See All</a>
                </div>
            </div>
            @foreach ($suggested_users as $user)
                <div class="row align-items-center mb-3">
                    <div class="col-auto">
                        <a href="{{route('profile.show', $user->id)}}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                        </a>
                    </div>
                    <div class="col ps-0 text-truncate">
                        <a href="{{route('profile.show', $user->id)}}" class="text-decoration-none text-dark fw-bold fs-6">
                            {{ $user->name }}
                        </a>
                    </div>
                    <div class="col-auto">
                        <form action="{{route('follow.store', $user->id)}}" method="post">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
        window.addEventListener('scroll', function () {
        const backToTop = document.getElementById('backToTopContainer');
        const scrollY = window.scrollY;
        const fullHeight = document.body.scrollHeight - window.innerHeight;

        if (scrollY >= fullHeight - 50) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.modal').forEach(modal => {
        const carousel = modal.querySelector('[id^=story-carousel-]');
        if (!carousel) return;
  
        const slides = carousel.querySelectorAll('.story-slide');
        let currentIndex = 0;
        const userId = carousel.dataset.userId;
  
        const showSlide = (index) => {
          slides.forEach(slide => slide.classList.add('d-none'));
          if (slides[index]) {
            slides[index].classList.remove('d-none');
          }
        };
  
        modal.querySelector('.btn-prev-story').addEventListener('click', () => {
          if (currentIndex > 0) {
            currentIndex--;
            showSlide(currentIndex);
          }
        });
  
        modal.querySelector('.btn-next-story').addEventListener('click', () => {
          if (currentIndex < slides.length - 1) {
            currentIndex++;
            showSlide(currentIndex);
          } else {
            const nextModal = modal.nextElementSibling;
            const bsModal = bootstrap.Modal.getInstance(modal);
            bsModal.hide();
  
            if (nextModal && nextModal.classList.contains('modal')) {
              const newModal = new bootstrap.Modal(nextModal);
              newModal.show();
            }
          }
        });
  
        // リセット
        modal.addEventListener('shown.bs.modal', () => {
          currentIndex = 0;
          showSlide(currentIndex);
        });
      });
    });
  </script>
  
@endsection
