{{-- @extends('layouts.app')

@section('title', $category->name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <h1>Category: {{ $category->name }}</h1>
    <p>{{ $category->description ?? 'No description available.' }}</p>

    @if($articles->count() > 0)
        <div class="list-group mt-4">
            @foreach($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $article->title }}</h5>
                        <small>{{ $article->created_at->format('M d, Y') }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit(strip_tags($article->content), 200) }}</p>
                    <small>Click to read more</small>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    @else
        <div class="alert alert-info mt-4">
            No articles found in this category.
        </div>
    @endif
@endsection --}}

@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-body text-center py-5">
            <div class="bg-primary bg-gradient rounded-circle p-3 d-inline-block mb-3">
                <i class="fas fa-folder fa-2x text-white"></i>
            </div>
            <h1>Категория: {{ $category->name }}</h1>
            <p class="lead">{{ $category->description ?? 'Описание категории отсутствует.' }}</p>
            <span class="badge bg-primary fs-6">{{ $articles->total() }} статей</span>
        </div>
    </div>

    @if($articles->count() > 0)
        <div class="row">
            @foreach($articles as $article)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary">{{ $article->category->name }}</span>
                                <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                            </div>
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('article.show', $article->slug) }}" class="btn btn-sm btn-primary">Читать далее</a>
                            @if($article->tags->count() > 0)
                                <div class="mt-2">
                                    @foreach($article->tags as $tag)
                                        <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h3>В этой категории пока нет статей</h3>
                <p class="text-muted">Попробуйте проверить позже или посмотреть другие категории.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Вернуться на главную</a>
            </div>
        </div>
    @endif
@endsection