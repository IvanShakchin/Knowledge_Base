<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Knowledge Base')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --primary-color: #818cf8;
            --primary-hover: #6366f1;
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            --bs-table-color: #9c9c9c;
        }
        [data-theme="dark"] p {
            color: #f1f5f9; /* Светлый цвет текста для тёмной темы */
        }


        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--card-bg);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .navbar-brand, .nav-link {
            color: var(--text-color) !important;
            font-weight: 600;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--gradient);
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background-color: var(--text-muted);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }

        .form-control, .form-select {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            border-color: var(--primary-color);
        }

        .table {
            color: var(--text-color);
            border-color: var(--border-color);
        }

        .table th {
            border-color: var(--border-color);
            font-weight: 600;
            background-color: var(--card-bg);
        }

        .table td {
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        .breadcrumb {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            box-shadow: var(--shadow);
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .list-group-item {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            transition: var(--transition);
        }

        .list-group-item:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .theme-switcher {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .theme-switcher:hover {
            transform: rotate(30deg);
        }

        footer {
            margin-top: auto;
            background-color: var(--card-bg);
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .article-content h2, .article-content h3, .article-content h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .article-content img {
            max-width: 100%;
            border-radius: 12px;
            margin: 1.5rem 0;
            box-shadow: var(--shadow);
        }

        .pagination .page-link {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient);
            border-color: var(--primary-color);
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .card {
                border-radius: 8px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .theme-switcher {
                width: 35px;
                height: 35px;
            }
        }

        [data-theme="dark"] .text-muted2 {
            color: #f1f5f9;
        }
        [data-theme="dark"] .text-muted2 {
            color: #f1f5f9;
        }

        [data-theme="dark"] .navbar-toggler {
            background: #cdcdcd82;
        }

        [data-theme="dark"] .card-title {
            color: #f1f5f9;
        }
        
        [data-theme="dark"] .article-content {
            color: #f1f5f9;
        }
        [data-theme="dark"] h1 {
            color: #f1f5f9;
        }        
        [data-theme="dark"] .breadcrumb-item {
            color: #f1f5f9;
        } 
        [data-theme="dark"] .fas {
            color: #f1f5f9;
        }         
        [data-theme="dark"] .mt-4 {
            color: #f1f5f9;
        } 
        [data-theme="dark"] .text-muted {
            background: #395584;
        }   
        [data-theme="dark"] ::placeholder {
            color: #3c5e80;
            opacity: 1; /* Убираем прозрачность */
        } 
        [data-theme="dark"] .mb-3 {
            color: #f1f5f9;
        } 
        [data-theme="dark"] .table {
            color: #f1f5f9;
        } 
        
        [data-theme="dark"] .card-body {
            color: #f1f5f9;
        } 
        
        /* Стили для древовидной структуры категорий */
        .list-group-item {
            transition: all 0.2s ease;
            border-left: none;
            border-right: none;
        }
        
        .list-group-item:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .list-group-item .badge {
            transition: all 0.2s ease;
        }
        
        .list-group-item:hover .badge {
            background-color: white !important;
            color: var(--primary-color) !important;
        }
        
        /* Убираем границы у вложенных list-group */
        .list-group-flush .list-group-flush {
            border: none;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>
                <span>База знаний</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: #cfcfcf solid 1px;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    </li>
                    @auth
                        @if(Auth::user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-1"></i> Панель администратора
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.articles.index') }}"><i class="fas fa-file-alt me-2"></i>Статьи</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i class="fas fa-folder me-2"></i>Категории</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tags.index') }}"><i class="fas fa-tag me-2"></i>Теги</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i>Пользователи</a></li>
                            </ul>
                        </li>
                        @endif
                    @endauth                  
                </ul>
                
                <form class="d-flex me-3" action="{{ route('search') }}" method="POST">
                    @csrf              
                    <input class="form-control me-2" type="search" name="q" placeholder="Найдётся всё" aria-label="Search" value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit" style="margin-right: 10px;"><i class="fas fa-search"></i></button>

                    <!-- Исправленный переключатель режимов поиска -->
                    <div class="form-check form-switch me-3 align-items-center d-flex">
                        <input type="hidden" name="search_type" id="searchTypeValue" value="{{ request('search_type', 'phrase') }}">
                        <input class="form-check-input" type="checkbox" id="searchTypeToggle" {{ request('search_type', 'phrase') == 'words' ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="searchTypeToggle">
                            <small id="searchTypeLabel">По словам</small>
                        </label>
                    </div>
                </form>

                <div class="d-flex align-items-center">
                    <button class="theme-switcher me-3" id="themeSwitcher">
                        <i class="fas fa-moon"></i>
                    </button>

                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Профиль</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Выйти</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Войти</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i>Регистрация</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4 fade-in">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">База знаний &copy; {{ date('Y') }}</p>
            <small class="text-muted2">Ваша база знаний для эффективной работы</small>
            <!-- Добавляем ссылку на инструкцию в футер -->
            <div class="mt-2">
                <a href="{{ route('instructions') }}" class="text-muted2 text-decoration-none">
                    <i class="fas fa-question-circle me-1"></i>Инструкция по использованию
                </a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Функция переключения темы
            const themeSwitcher = document.getElementById('themeSwitcher');
            const themeIcon = themeSwitcher.querySelector('i');
            const html = document.documentElement;
            
            // Проверяем сохраненную тему или устанавливаем по времени
            const savedTheme = localStorage.getItem('theme');
            const currentHour = new Date().getHours();
            const isDayTime = currentHour >= 6 && currentHour < 18;
            
            if (savedTheme) {
                html.setAttribute('data-theme', savedTheme);
                themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            } else {
                const autoTheme = isDayTime ? 'light' : 'dark';
                html.setAttribute('data-theme', autoTheme);
                themeIcon.className = autoTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                localStorage.setItem('theme', autoTheme);
            }
            
            // Обработчик переключения темы
            themeSwitcher.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                localStorage.setItem('theme', newTheme);
            });
            
            // Управление переключателем режимов поиска
            const searchTypeToggle = document.getElementById('searchTypeToggle');
            const searchTypeValue = document.getElementById('searchTypeValue');
            const searchTypeLabel = document.getElementById('searchTypeLabel');
            
            if (searchTypeToggle && searchTypeValue && searchTypeLabel) {
                // Устанавливаем начальное состояние
                updateSearchTypeLabel();
                
                // Обработчик изменения переключателя
                searchTypeToggle.addEventListener('change', function() {
                    if (this.checked) {
                        searchTypeValue.value = 'words';
                    } else {
                        searchTypeValue.value = 'phrase';
                    }
                    updateSearchTypeLabel();
                });
                
                function updateSearchTypeLabel() {
                    if (searchTypeValue.value === 'words') {
                        searchTypeLabel.textContent = 'По словам';
                    } else {
                        searchTypeLabel.textContent = 'По фразам';
                    }
                }
            }
            
            // Анимация появления элементов при прокрутке
            const animatedElements = document.querySelectorAll('.fade-in');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>