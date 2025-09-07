@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category', $article->category->slug) }}">{{ $article->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
        </ol>
    </nav>
    
    <article class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="card-title">{{ $article->title }}</h1>
                    <div class="text-muted">
                        <i class="far fa-calendar me-1"></i> Опубликовано {{ $article->created_at->format('d.m.Y') }} 
                        <i class="fas fa-folder ms-3 me-1"></i> в 
                        <a href="{{ route('category', $article->category->slug) }}" class="text-decoration-none">{{ $article->category->name }}</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="articleActions" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="articleActions">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-share me-2"></i>Поделиться</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Распечатать</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Сообщить о проблеме</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="article-content">
                {!! $article->content !!}
            </div>
            
            @if($article->tags->count() > 0)
                <div class="mt-4 pt-3 border-top">
                    <strong><i class="fas fa-tags me-2"></i>Теги:</strong>
                    @foreach($article->tags as $tag)
                        <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </article>

    @if($article->media->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Прикрепленные файлы</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($article->media as $media)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            @if(in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                            <img src="{{ Storage::url($media->path) }}" class="card-img-top" alt="{{ $media->original_name }}" style="height: 150px; object-fit: cover;">
                            @elseif($media->mime_type === 'application/pdf')
                            <div class="card-body text-center py-4">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            </div>
                            @else
                            <div class="card-body text-center py-4">
                                <i class="fas fa-file fa-3x text-secondary"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title text-truncate">{{ $media->original_name }}</h6>
                                <a href="{{ Storage::url($media->path) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-download me-1"></i>Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ URL::previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад
        </a>
        @auth
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Редактировать
            </a>
        @endauth
    </div>
@endsection