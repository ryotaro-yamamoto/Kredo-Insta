@extends('layouts.app')

@section('title', $user->name)

@section('content')
  <div class="row justify-content-center">
    <div class="col-8">
    <form action="{{route('profile.update', Auth::user()->id)}}" method="post" class="bg-white rounded-3 p-5"
      enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <h2 class="h3 mb-3 fw-light text-muted">Update Profile</h2>

      <div class="row mb-3">
      <div class="col-4">
        @if ($user->avatar)
      <img src="{{$user->avatar}}" alt="{{$user->name}}"
      class="img-thumbnail rounded-circle d-block mx-auto avatar-lg">
      @else
      <i class="fa-solid fa-circle-user text-secondary icon-lg d-block text-center"></i>
      @endif
      </div>
      <div class="col-auto align-self-end">
        <input type="file" name="avatar" id="avatar" class="form-control form-control-sm mt-1"
        aria-describedby="avatar-info">
        <div class="form-text" id="avatar-info">
        Acceptable formats: jpeg, jpg, png, gif only.<br>
        Max file size is 1048kb.
        </div>
        {{-- error --}}
        @error('avatar')
      <div class="text-danger small">{{ $message }}</div>
      @enderror
      </div>
      </div>
      <div class="mb-3">
      <label for="name" class="form-label fw-bold">Name</label>
      <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" autofocus>
      {{-- error --}}
      @error('name')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>
      <div class="mb-3">
      <label for="email" class="form-label fw-bold">E-Mail Address</label>
      <input type="text" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}">
      {{-- error --}}
      @error('email')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>
      <div class="mb-3">
      <label for="introduction" class="form-label fw-bold">Introduction</label>
      <textarea name="introduction" id="introduction" rows="5" class="form-control"
        placeholder="Describe yourself">{{old('introduction', $user->introduction)}}</textarea>
      {{-- error --}}
      @error('introduction')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>
      <div>
        <label>興味</label>
        @foreach ($interests as $interest)
            <div>
                <input type="checkbox" name="interests[]" value="{{ $interest->id }}"
                    {{ $user->interests->contains($interest->id) ? 'checked' : '' }}>
                {{ $interest->name }}
            </div>
        @endforeach
    </div>

      {{-- password --}}{{-- New(RIKO) --}}
      <hr class="my-4">

      <h2 class="h5 mb-3 fw-light text-muted">Change Password</h2>

      <div class="mb-3">
      <label for="current_password" class="form-label fw-bold">Current Password</label>
      <input type="password" name="current_password" id="current_password" class="form-control">
      @error('current_password')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>

      <div class="mb-3">
      <label for="new_password" class="form-label fw-bold">New Password</label>
      <input type="password" name="new_password" id="new_password" class="form-control">
      @error('new_password')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>

      <div class="mb-3">
      <label for="new_password_confirmation" class="form-label fw-bold">Confirm New Password</label>
      <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
      @error('new_password_confirmation')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
      </div>


      <button type="submit" class="btn btn-warning px-5">Save</button>
    </form>
    </div>
  </div>
@endsection