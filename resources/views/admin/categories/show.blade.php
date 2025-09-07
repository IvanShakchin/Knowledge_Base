@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Category Details: {{ $category->name }}</h5>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to Categories</a>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $category->name }}
                        </div>

                        <div class="mb-3">
                            <strong>Slug:</strong> {{ $category->slug }}
                        </div>

                        <div class="mb-3">
                            <strong>Description:</strong> 
                            {{ $category->description ?? 'No description provided.' }}
                        </div>

                        <div class="mb-3">
                            <strong>Parent Category:</strong> 
                            {{ $category->parent->name ?? 'None' }}
                        </div>

                        <div class="mb-3">
                            <strong>Child Categories:</strong>
                            @if($category->children->count() > 0)
                                <ul>
                                    @foreach($category->children as $child)
                                        <li>{{ $child->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No child categories.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Articles in this category:</strong> {{ $category->articles->count() }}
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning me-md-2">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This will also remove this category as parent from any child categories.')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection