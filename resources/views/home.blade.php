@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Добро пожаловать в нашу базу знаний</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Найдите ответы на распространенные вопросы и узнайте, как пользоваться нашими продуктами.</p>
                    
                    <!-- Добавляем кнопку для перехода к инструкции -->
                    <div class="text-center mb-4" style="text-align: left!important;">
                        <a href="{{ route('instructions') }}" class="btn btn-info" style="background: #9f88ed40;color: #7d7e7e;">
                            <i class="fas fa-book me-2"></i>Инструкция по использованию
                        </a>
                    </div>
                    
                    <div class="d-flex mt-4">
                        <div class="me-4 text-center">
                            <div class="bg-primary bg-gradient rounded-circle p-3 d-inline-block">
                                <i class="fas fa-book fa-2x text-white"></i>
                            </div>
                            <p class="mt-2 mb-0 fw-bold">Статьи</p>
                            <p class="text-muted2">Полезные материалы</p>
                        </div>
                        
                        <div class="me-4 text-center">
                            <div class="bg-success bg-gradient rounded-circle p-3 d-inline-block">
                                <i class="fas fa-tags fa-2x text-white"></i>
                            </div>
                            <p class="mt-2 mb-0 fw-bold">Категории</p>
                            <small class="text-muted2">Организация контента</small>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-info bg-gradient rounded-circle p-3 d-inline-block">
                                <i class="fas fa-search fa-2x text-white"></i>
                            </div>
                            <p class="mt-2 mb-0 fw-bold">Поиск</p>
                            <small class="text-muted2">Быстрый доступ</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <h3 class="mb-4"><i class="fas fa-history me-2"></i>Последние статьи</h3>
            <div class="row">
                @foreach($recentArticles as $article)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">{{ $article->category->name }}</span>
                                    <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                                </div>
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="{{ route('article.show', $article->slug) }}" class="btn btn-sm btn-primary">Читать далее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Категории</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @include('partials.categories-tree', ['categories' => $categories, 'level' => 0])
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Нужна помощь?</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Не нашли то, что искали? Свяжитесь с нашей службой поддержки.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-envelope me-2"></i>Написать в поддержку</a>
                </div>
            </div>
        </div>
    </div>
@endsection