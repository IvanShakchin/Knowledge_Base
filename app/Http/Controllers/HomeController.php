<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем все категории с отношениями
        $allCategories = Category::with('articles', 'children')->get();
        
        // Строим древовидную структуру
        $categories = $this->buildTree($allCategories);
        
        $recentArticles = Article::visibleTo(auth()->user())->latest()->take(5)->get();
        
        return view('home', compact('categories', 'recentArticles'));
    }

    public function category(Category $category)
    {
        $articles = $category->articles()
            ->visibleTo(auth()->user())
            ->paginate(10);
            
        return view('category', compact('category', 'articles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $searchType = $request->input('search_type', 'phrase');

        if (empty($query)) {
            $articles = Article::visibleTo(auth()->user())->paginate(10);
        } else {
            if ($searchType === 'words') {
                $words = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
                
                $articles = Article::visibleTo(auth()->user())
                                ->where(function($q) use ($words) {
                                    foreach ($words as $word) {
                                        $word = str_replace(['%', '_'], ['\%', '\_'], $word);
                                        $q->orWhere(function($innerQ) use ($word) {
                                            $innerQ->where('title', 'like', "%{$word}%")
                                                ->orWhere('content', 'like', "%{$word}%")
                                                ->orWhereHas('tags', function($tagQuery) use ($word) {
                                                    $tagQuery->where('name', 'like', "%{$word}%");
                                                });
                                        });
                                    }
                                })
                                ->with('tags', 'category')
                                ->paginate(10);
            } else {
                $searchTerm = str_replace(['%', '_'], ['\%', '\_'], $query);
                
                $articles = Article::visibleTo(auth()->user())
                                ->where(function($q) use ($searchTerm) {
                                    $q->where('title', 'like', "%{$searchTerm}%")
                                        ->orWhere('content', 'like', "%{$searchTerm}%")
                                        ->orWhereHas('tags', function($tagQuery) use ($searchTerm) {
                                            $tagQuery->where('name', 'like', "%{$searchTerm}%");
                                        });
                                })
                                ->with('tags', 'category')
                                ->paginate(10);
            }
        }
        
        return view('search', compact('articles', 'query', 'searchType'));
    }

    /**
     * Строит древовидную структуру категорий
     */
    private function buildTree($categories, $parentId = null)
    {
        $tree = collect();
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id);
                
                // Добавляем количество статей во всех дочерних категориях
                $totalArticles = $category->articles->count();
                if ($children->isNotEmpty()) {
                    foreach ($children as $child) {
                        $totalArticles += $child->articles_count_recursive ?? 0;
                    }
                }
                
                $category->articles_count_recursive = $totalArticles;
                
                if ($children->isNotEmpty()) {
                    $category->children = $children;
                }
                
                $tree->push($category);
            }
        }
        
        return $tree;
    }
}