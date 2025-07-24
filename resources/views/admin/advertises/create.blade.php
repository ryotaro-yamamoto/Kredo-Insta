@extends('layouts.app')

@section('title', 'Create Advertise')

@section('content')
    <h2>Create Advertise</h2>

    <form action="{{ route('admin.advertises.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <input type="file" class="form-control" name="image" id="image" aria-describedby="image">
            <div id="image" class="form-text">
                The acceptable formats are jpeg, png, and gif only.<br>
                Max file size is 1048kb.
            </div>
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Target Interests</label>
            <div>
                @foreach ($interests as $interest)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="interests[]" id="interest_{{ $interest->id }}" value="{{ $interest->id }}">
                        <label class="form-check-label" for="interest_{{ $interest->id }}">
                            {{ $interest->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        

        <button type="submit" class="btn btn-primary">Post Advertisement</button>
    </form>
@endsection