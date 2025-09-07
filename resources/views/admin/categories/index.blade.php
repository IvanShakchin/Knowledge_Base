@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Category</a>
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
                <th style="background: #c6c6c6;">Parent Category</th>
                <th style="background: #c6c6c6;">Articles Count</th>
                <th style="background: #c6c6c6;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent->name ?? 'None' }}</td>
                    <td>{{ $category->articles_count }}</td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will also remove this category as parent from any child categories.')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
@endsection