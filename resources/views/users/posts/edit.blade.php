@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="row">
  <div class="col-10 mx-auto">
    <form action="{{route('post.update', $post->id)}}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      <div class="row">
        <div class="col">
          <label for="category" class="form-label"><b>Category</b> <span class="text-secondary">(up to 3)</span></label>
        </div>
      </div>
      <div class="mb-3">
        @foreach ($all_categories as $category)
        <div class="form-check form-check-inline">
          @if (in_array($category->id, $selected_categories))
            <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" id="{{ $category->name }}" checked>
              
          @else
            <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" id="{{ $category->name }}">
              
          @endif
          <label class="form-check-label" for="{{ $category->name}}">
            {{ $category->name }}
          </label>
        </div>
        @endforeach
        @error('category')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <label for="description" class="form-label"><b>Description</b></label>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <textarea class="form-control" name="description" id="description" rows="3" placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
        </div>
        @error('description')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      @if ($post->images->count())
        <div id="carousel-edit-{{ $post->id }}" class="carousel slide mb-3 w-50" data-bs-ride="carousel">
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
          <div class="carousel-inner" style="height:400px;">
            @foreach ($post->images as $index => $image)
              <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                <div class="d-flex align-items-center justify-content-center h-100 bg-white">
                  <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100 object-fit-contain" alt="Image {{ $index + 1 }}">
                </div>
              </div>
            @endforeach
          </div>
          @if ($post->images->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-edit-{{ $post->id }}" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" style="background-color: black;"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-edit-{{ $post->id }}" data-bs-slide="next">
              <span class="carousel-control-next-icon" style="background-color: black;"></span>
            </button>
          @endif
        </div>
        @endif

      <div class="mb-3">
        <div id="image-upload-wrapper">
          <input type="file" name="images[]" class="form-control image-input mb-2 w-50" accept="image/*">
        </div>
        @error('images')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <button type="submit" class="btn btn btn-warning">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const maxImages = 5;
    const wrapper = document.getElementById('image-upload-wrapper');
    const previewWrapper = document.getElementById('image-preview-wrapper');

    wrapper.addEventListener('change', function (e) {
      if (!e.target.classList.contains('image-input')) return;

      const files = e.target.files;
      if (files.length > 0) {
        // 画像プレビューを追加
        const file = files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'img-thumbnail mb-2';
          img.style.maxWidth = '300px';
          previewWrapper.appendChild(img);
        };
        reader.readAsDataURL(file);

        // 入力欄が5未満なら次のinputを追加
        const currentInputs = wrapper.querySelectorAll('.image-input');
        if (currentInputs.length < maxImages) {
          const newInput = document.createElement('input');
          newInput.type = 'file';
          newInput.name = 'images[]';
          newInput.className = 'form-control image-input mb-2';
          newInput.accept = 'image/*';
          wrapper.appendChild(newInput);
        }
      }
    });
  });
</script>
@endsection