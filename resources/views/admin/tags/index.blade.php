@extends('layouts.app')

@section('title', 'Manage Tags')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Create Tag</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="background: #c6c6c6;">Name</th>
                <th style="background: #c6c6c6;">Articles Count</th>
                <th style="background: #c6c6c6;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->articles_count }}</td>
                    <td>
                        <a href="{{ route('admin.tags.show', $tag) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tags->links() }}
@endsection