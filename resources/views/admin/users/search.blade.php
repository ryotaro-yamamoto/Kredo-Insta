@extends('layouts.app')

@section('title', 'Admin: Users')

@section('content')
  @if(request('search_users'))
    <p class="mb-3 text-muted">Search results for: <strong>{{ $search }}</strong></p>
  @endif

  @if ($all_users->isEmpty())
    <div class="alert alert-info text-center">
      <i class="fa-solid fa-circle-success"></i> No users found.
    </div>
  @else
    <table class="table table-hover align-middle bg-success border text-secondary">
      <thead class="small table-success text-secondary">
        <tr>
          <th></th>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>CREATED AT</th>
          <th>STATUS</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($all_users as $user)
          <tr>
            <td>
              @if ($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle d-block mx-auto avatar-md">
              @else
                <i class="fa-solid fa-circle-user text-secondary icon-md d-block mx-auto"></i>
              @endif
            </td>
            <td>
              <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
              @if ($user->trashed())
                <i class="fa-solid fa-circle text-secondary"></i>&nbsp; Inactive
              @else
                <i class="fa-solid fa-circle text-success"></i>&nbsp; Active
              @endif
            </td>
            <td>
              @if (Auth::user()->id !== $user->id)
                <div class="dropdown">
                  <button class="btn btn-sm" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>

                  <div class="dropdown-menu">
                    @if ($user->trashed())
                      <button class="dropdown-item text-secondary" data-bs-toggle="modal" data-bs-target="#activate-user-{{$user->id}}">
                        <i class="fa-solid fa-user-check"></i> Activate {{ $user->name }}
                      </button>
                    @else
                      <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-user-{{$user->id}}">
                        <i class="fa-solid fa-user-slash"></i> Deactivate {{ $user->name }}
                      </button>
                    @endif
                  </div>
                </div>
                {{-- include modal here --}}
                @include('admin.users.modals.status')
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $all_users->links() }}
    </div>
  @endif
@endsection
