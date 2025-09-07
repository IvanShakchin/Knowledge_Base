@extends('layouts.app')

@section('title', $tag->name)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tag Details: {{ $tag->name }}</h5>
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Back to Tags</a>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $tag->name }}
                        </div>

                        <div class="mb-3">
                            <strong>Slug:</strong> {{ $tag->slug }}
                        </div>

                        <div class="mb-3">
                            <strong>Articles with this tag:</strong> {{ $tag->articles->count() }}
                        </div>

                        @if($tag->articles->count() > 0)
                        <div class="mb-3">
                            <strong>Articles:</strong>
                            <ul class="list-group mt-2">
                                @foreach($tag->articles as $article)
                                    <li class="list-group-item">
                                        <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                                        <span class="badge bg-secondary ms-2">{{ $article->status }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-warning me-md-2">Edit</a>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection