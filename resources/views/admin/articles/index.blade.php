@extends('layouts.app')

@section('title', 'Manage Articles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Create Article</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="background: #c6c6c6;">Title</th>
                <th style="background: #c6c6c6;">Category</th>
                <th style="background: #c6c6c6;">Status</th>
                <th style="background: #c6c6c6;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->category->name }}</td>
                    <td>
                        <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'draft' ? 'warning' : 'info') }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('article.show', $article->slug) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $articles->links() }}
@endsection