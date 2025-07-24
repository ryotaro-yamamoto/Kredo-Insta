@extends('layouts.app') 

@section('title', '広告一覧')

@section('content')
<div class="container">
    <h1>Advertisement List</h1>

    <a href="{{ route('admin.advertises.create') }}" class="btn btn-primary mb-3">＋ New Ad</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($ads->isEmpty())
        <p>No advertisements have been registered yet.</p>
    @else
        <table class="table table-hover align-middle bg-white border text-secondary">
            <thead class="small table-primary text-secondary">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Interest</th>
                    <th>Create Time</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ads as $ad)
                <tr>
                    <td>{{ $ad->id }}</td>
                    <td><img src="{{ $ad->image }}" alt="{{ $ad->id }}" class="mx-0 image-md img-thumbnail"></td>
                    <td>
                        @foreach($ad->interests as $interest)
                            <span class="mx-0 badge bg-secondary">{{ $interest->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $ad->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.advertises.edit', $ad->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('admin.advertises.destroy', $ad->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection