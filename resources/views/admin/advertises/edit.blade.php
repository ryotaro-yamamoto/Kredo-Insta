@extends('layouts.app')

@section('title', 'Edit Advertise')

@section('content')
    <h2>Edit Advertise</h2>

    <form action="{{ route('admin.advertises.update', $advertise->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- 説明 --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $advertise->description) }}</textarea>
        </div>

        {{-- 画像 --}}
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="{{ $advertise->image }}" alt="Current Image" class="img-thumbnail image-md mb-2">
            <input type="file" class="form-control" name="image" id="image">
            <div id="imageHelp" class="form-text">
                Upload to replace the image. Acceptable formats: jpeg, png, gif. Max size: 1048kb.
            </div>
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- 興味カテゴリー --}}
        <div class="mb-3">
            <label class="form-label">Target Interests</label>
            <div>
                @foreach ($interests as $interest)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="interests[]"
                            id="interest_{{ $interest->id }}"
                            value="{{ $interest->id }}"
                            {{ $advertise->interests->contains($interest->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="interest_{{ $interest->id }}">
                            {{ $interest->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Advertisement</button>
    </form>
@endsection