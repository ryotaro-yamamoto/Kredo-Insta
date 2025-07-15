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

      <div class="row mb-4">
        <div class="col-6">
          <label for="image" class="form-label"><b>Image</b></label>
          <img src="{{$post->image}}" alt="post id {{$post->id}}" class="img-thumbnail w-100">
          <input type="file" class="form-control mt-1" name="image" id="image" aria-describedby="image-info">
          <div id="image" class="form-text">
            The acceptable formats are jpeg, png, and gif only.<br>
            Max file size is 1048kb.
          </div>
          @error('image')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>
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
