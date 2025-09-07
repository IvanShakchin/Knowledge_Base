<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\UserController;


// Возможно эти три подключения придется закоментировать
// use PDO;
// use RecursiveIteratorIterator;
// use RecursiveDirectoryIterator;


    //Public routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::post('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/category/{category:slug}', [HomeController::class, 'category'])->name('category');
    Route::get('/article/{article:slug}', [ArticleController::class, 'show'])->name('article.show');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.articles.index');
        })->name('index'); // Теперь можно использовать route('admin.index')
        
        Route::resource('articles', ArticleController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('tags', TagController::class);

        // Маршруты управления пользователями
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::delete('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::patch('/users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');
        Route::patch('/users/{user}/remove-admin', [UserController::class, 'removeAdmin'])->name('users.removeAdmin');
    
    });

    Route::delete('admin/articles/{article}/files/{media}', [ArticleController::class, 'destroyFile'])->name('admin.articles.files.destroy');

    Route::post('articles/{article}/restore/{history}', [ArticleController::class, 'restoreVersion'])
    ->name('admin.articles.restore');

    // Добавляем маршрут для страницы инструкции
    Route::get('/instructions', function () {
        return view('instructions');
    })->name('instructions'); 

    // Функция сканирования директории
    function scanDirectory($dir, $basePath = '') {
        $result = [];
        $stats = ['files' => 0, 'folders' => 0];
    
        $items = scandir($dir);
        foreach($items as $item) {
            if($item == '.' || $item == '..' || in_array($item, ['vendor', 'node_modules', '.git'])) continue;
        
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            $relativePath = ($basePath ? $basePath . '/' : '') . $item;
        
            if(is_dir($path)) {
                $stats['folders']++;
                $children = scanDirectory($path, $relativePath);
                $stats['files'] += $children['stats']['files'];
                $stats['folders'] += $children['stats']['folders'];
            
                $result[] = [
                    'type' => 'folder',
                    'name' => $item,
                    'path' => $relativePath,
                    'size' => round(calculateFolderSize($path) / 1024, 1),
                    'children' => $children['tree'],
                    'stats' => $children['stats']
                ];
            } else {
                $stats['files']++;
                $result[] = [
                    'type' => 'file',
                    'name' => $item,
                    'path' => $relativePath,
                    'size' => round(filesize($path) / 1024, 1)
                ];
            }
        }
    
        return ['tree' => $result, 'stats' => $stats];
    }




    

    // Функция расчета размера папки
    function calculateFolderSize($dir) {
        $size = 0;
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach($files as $file) {
            if($file->isFile() && !str_contains($file->getPathname(), '.git')) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    // Главная страница VCA
    // Route::match(['get', 'post'], 'vca', function (Request $request) {
    //     $accessPassword = env('VCA_ACCESS_PASSWORD', 'afyljhby');
    //     $authenticated = Session::has('vca_authenticated');

    //     if ($request->isMethod('post')) {
    //         if ($request->input('password') === $accessPassword) {
    //             Session::put('vca_authenticated', true);
    //             $authenticated = true;
    //         } else {
    //             Session::flash('error', 'Неверный пароль!');
    //             return redirect()->route('vca.index');
    //         }
    //     }

    //     if (!$authenticated) {
    //         return view('vca.login');
    //     }

    //     return view('vca.index');
    // })->name('vca.index');

    // Сканирование файловой системы
    // Route::post('vca/scan', function () {
    //     if (!Session::has('vca_authenticated')) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     try {
    //         // Сканируем корневую директорию проекта
    //         $rootDir = base_path();
            
    //         $result = scanDirectory($rootDir);
            
    //         // Формируем корневой элемент
    //         $response = [
    //             'type' => 'folder',
    //             'name' => basename($rootDir),
    //             'path' => '',
    //             'size' => round(calculateFolderSize($rootDir) / 1024, 1),
    //             'children' => $result['tree'],
    //             'stats' => $result['stats']
    //         ];
            
    //         return response()->json([
    //             'tree' => [$response],
    //             'stats' => $response['stats']
    //         ]);
    //     } catch(Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // })->name('vca.scan');

    // Экспорт данных
    // Route::post('vca/export', function (Request $request) {
    //     if (!Session::has('vca_authenticated')) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Функция для рекурсивного отображения структуры
    //     function renderStructure($node, $indent = 0) {
    //         $output = '';
    //         $prefix = str_repeat('  ', $indent);
        
    //         if($node['type'] === 'folder') {
    //             $output .= $prefix . "📁 {$node['name']} ({$node['size']} KB)\n";
            
    //             if(isset($node['children'])) {
    //                 foreach($node['children'] as $child) {
    //                     $output .= renderStructure($child, $indent + 1);
    //                 }
    //             }
    //         } else {
    //             $output .= $prefix . "📄 {$node['name']} ({$node['size']} KB)\n";
    //         }
        
    //         return $output;
    //     }
        
    //     try {
    //         $data = $request->json()->all();
    //         $files = $data['files'] ?? [];
    //         $structure = $data['structure'] ?? null;
    //         $includeDb = $data['includeDb'] ?? true;
        
    //         $output = "ЭКСПОРТ ПРОЕКТА\n\n";
    //         $output .= "ТИП ЭКСПОРТА: " . ($includeDb ? "С БАЗОЙ ДАННЫХ" : "БЕЗ БАЗЫ ДАННЫХ") . "\n\n";
        
    //         $output .= "================================\n";
    //         $output .= "СТРУКТУРА ПРОЕКТА\n";
    //         $output .= "================================\n\n";
        
    //         if($structure && isset($structure['tree'][0])) {
    //             $output .= renderStructure($structure['tree'][0]);
    //         } else {
    //             $output .= "Структура проекта недоступна\n";
    //         }
        
    //         $output .= "\n\n================================\n";
    //         $output .= "СОДЕРЖИМОЕ ВЫБРАННЫХ ФАЙЛОВ\n";
    //         $output .= "================================\n\n";
        
    //         foreach($files as $file) {
    //             $fullPath = base_path($file);
            
    //             if(!file_exists($fullPath)) {
    //                 $output .= "ФАЙЛ НЕ НАЙДЕН: $file\n\n";
    //                 continue;
    //             }
            
    //             $output .= "📄 ФАЙЛ: $file\n";
    //             $output .= "📏 РАЗМЕР: " . round(filesize($fullPath) / 1024, 1) . " KB\n";
    //             $output .= "🔍 СОДЕРЖИМОЕ:\n" . file_get_contents($fullPath) . "\n\n";
    //             $output .= str_repeat('-', 50) . "\n\n";
    //         }
        
    //         if ($includeDb) {
    //             $output .= "\n\n================================\n";
    //             $output .= "СТРУКТУРА БАЗЫ ДАННЫХ\n";
    //             $output .= "================================\n\n";
            
    //             try {
    //                 $pdo = new PDO(
    //                     "mysql:host=".env('DB_HOST').";dbname=".env('DB_DATABASE'), 
    //                     env('DB_USERNAME'), 
    //                     env('DB_PASSWORD')
    //                 );
    //                 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
    //                 $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                
    //                 foreach($tables as $table) {
    //                     $output .= "📊 ТАБЛИЦА: $table\n";
                    
    //                     $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
                    
    //                     foreach($columns as $col) {
    //                         $output .= "  ├─ 🏷️ КОЛОНКА: {$col['Field']}\n";
    //                         $output .= "  │  ├─ ТИП: {$col['Type']}\n";
    //                         $output .= "  │  ├─ NULL: {$col['Null']}\n";
    //                         $output .= "  │  ├─ КЛЮЧ: {$col['Key']}\n";
    //                         $output .= "  │  ├─ ПО УМОЛЧАНИЮ: {$col['Default']}\n";
    //                         $output .= "  │  └─ ДОП: {$col['Extra']}\n";
    //                     }
    //                     $output .= "\n";
    //                 }
    //             } catch(PDOException $e) {
    //                 $output .= "❌ ОШИБКА ПОДКЛЮЧЕНИЯ К БАЗЕ ДАННЫХ: " . $e->getMessage() . "\n";
    //             }
    //         } else {
    //             $output .= "\n\n[ЭКСПОРТ БЕЗ СТРУКТУРЫ БАЗЫ ДАННЫХ]\n";
    //         }
        
    //         $fileName = $includeDb ? 'project_export_with_db.txt' : 'project_export_without_db.txt';
            
    //         return Response::make($output, 200, [
    //             'Content-Type' => 'text/plain',
    //             'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
    //         ]);
    //     } catch(Exception $e) {
    //         return response('❌ ОШИБКА ЭКСПОРТА: ' . $e->getMessage(), 500);
    //     }
    // })->name('vca.export');

    // Выход из системы
    // Route::get('vca/logout', function () {
    //     Session::forget('vca_authenticated');
    //     return redirect()->route('vca.index');
    // })->name('vca.logout');

require __DIR__.'/auth.php';