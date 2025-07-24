@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="row">
  <div class="col-10 mx-auto">
    <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col">
          <label for="category" class="form-label"><b>Category</b> <span class="text-secondary">(up to 3)</span></label>
        </div>
      </div>
      <div class="mb-3">
        @foreach ($all_categories as $category)
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" id="{{ $category->name }}" {{ in_array($category->id, old('category', [])) ? 'checked' : '' }}>
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
          <textarea class="form-control" name="description" id="description" rows="3" placeholder="What's on your mind?">{{ old('description') }}</textarea>
        </div>
        @error('description')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
  
      <div class="row">
        <div class="col">
          <label for="image" class="form-label"><b>Image</b></label>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <div id="image-upload-wrapper">
            <div class="mb-2">
                <input type="file" name="images[]" class="form-control image-input">
            </div>
          </div>

          @error('images')
            <div class="text-danger small">{{ $message }}</div>
          @enderror

          @error('images.*')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col">
          <button type="submit" class="btn btn btn-primary">Post</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('image-upload-wrapper');

    // 最初の1つのinputを取得
    const initialInput = wrapper.querySelector('.image-input');

    initialInput.addEventListener('change', handleInputChange);

    function handleInputChange(e) {
        // 現在のinputの数
        const inputs = wrapper.querySelectorAll('.image-input');
        const currentInput = e.target;

        // ファイルが選ばれていて、inputが5つ未満なら追加
        if (currentInput.files.length > 0 && inputs.length < 5) {
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'images[]';
            newInput.classList.add('form-control', 'image-input');
            newInput.classList.add('mb-2');
            wrapper.appendChild(newInput);

            // 次のinputにも同じイベントリスナを追加
            newInput.addEventListener('change', handleInputChange);
        }

        // 5つ以上なら追加しない
        if (inputs.length >= 5) {
            console.log('Maximum 5 images reached.');
        }
    }
});
</script>
