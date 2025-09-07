{{-- @extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search Results</li>
        </ol>
    </nav>

    <h1>Search Results @if(!empty($query)) for "{{ $query }}" @endif</h1>
    
    @if(!empty($query))
        <div class="alert alert-info">
            Search type: <strong>{{ $searchType == 'words' ? 'By any word (OR)' : 'By exact phrase' }}</strong>
        </div>
    @endif
@if(!empty($query))
        @if($articles->count() > 0)
            <p>Found {{ $articles->total() }} results.</p>
            
            <div class="list-group mt-4">
                @foreach($articles as $article)
                    <a href="{{ route('article.show', $article->slug) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $article->title }}</h5>
                            <small>{{ $article->created_at->format('M d, Y') }}</small>
                        </div>
                        <p class="mb-1">{{ Str::limit(strip_tags($article->content), 200) }}</p>
                        <div class="mt-2">
                            <small>Category: {{ $article->category->name }}</small>
                            @if($article->tags->count() > 0)
                                <div class="mt-1">
                                    <small>Tags: 
                                        @foreach($article->tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        @endforeach
                                    </small>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        @else
            <div class="alert alert-info mt-4">
                No articles found for your search.
            </div>
        @endif
    @else
        <div class="alert alert-warning mt-4">
            Please enter a search term.
        </div>
    @endif
@endsection  --}}

@extends('layouts.app')

@section('title', 'Результаты поиска')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-search me-2"></i>Результаты поиска</h1>
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('search') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-lg" name="q" value="{{ $query }}" placeholder="Введите поисковый запрос...">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-lg" name="search_type">
                        <option value="phrase" {{ $searchType == 'phrase' ? 'selected' : '' }}>По фразе</option>
                        <option value="words" {{ $searchType == 'words' ? 'selected' : '' }}>По словам</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Поиск</button>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($query))
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Найдено <strong>{{ $articles->total() }}</strong> результатов по запросу: <strong>"{{ $query }}"</strong>
        </div>
    @endif

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
        @if(!empty($query))
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>Ничего не найдено</h3>
                    <p class="text-muted">Попробуйте изменить поисковый запрос или использовать другие ключевые слова.</p>
                </div>
            </div>
        @endif
    @endif
@endsection