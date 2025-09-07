<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ArticleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category', 'tags')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'status' => 'required|in:draft,published,published_auth,pending',
        ]);

        $article = new Article($validated);
        $article->author_id = Auth::id();
        $article->slug = Str::slug($request->title);
        $article->save();

        if (isset($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('articles/' . $article->id, 'public');
                
                $article->media()->create([
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Сохраняем историю создания
        ArticleHistory::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'title' => $article->title,
            'content' => $article->content,
            'category_id' => $article->category_id,
            'status' => $article->status,
            'tags' => $article->tags->pluck('id')->toArray(),
            'changes' => ['type' => 'created']
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        if ($article->status === Article::STATUS_PUBLISHED_AUTH && !auth()->check()) {
            abort(403, 'This article is only available for authenticated users.');
        }
        
        if ($article->status === Article::STATUS_PENDING) {
            abort(404);
        }
        
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $histories = $article->histories()->with('user')->latest()->get();
        
        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'histories'));
    }

    public function update(Request $request, Article $article)
    {
        $originalData = [
            'title' => $article->title,
            'content' => $article->content,
            'category_id' => $article->category_id,
            'status' => $article->status,
            'tags' => $article->tags->pluck('id')->toArray()
        ];

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'status' => 'required|in:draft,published,published_auth,pending',
        ]);

        $article->update($validated);

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('articles/' . $article->id, 'public');
                
                $article->media()->create([
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Определяем изменения
        $changes = [];
        foreach ($validated as $key => $value) {
            if ($key === 'tags') {
                $oldTags = $originalData['tags'];
                $newTags = $value;
                
                if ($oldTags != $newTags) {
                    $changes['tags'] = [
                        'old' => $oldTags,
                        'new' => $newTags
                    ];
                }
            } elseif ($originalData[$key] != $value) {
                $changes[$key] = [
                    'old' => $originalData[$key],
                    'new' => $value
                ];
            }
        }

        // Сохраняем историю изменений
        ArticleHistory::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'title' => $article->title,
            'content' => $article->content,
            'category_id' => $article->category_id,
            'status' => $article->status,
            'tags' => $article->tags->pluck('id')->toArray(),
            'changes' => $changes
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }

    public function destroyFile(Article $article, Media $media)
    {
        Storage::delete($media->path);
        $media->delete();     
        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    public function restoreVersion(Article $article, ArticleHistory $history)
    {
        $article->update([
            'title' => $history->title,
            'content' => $history->content,
            'category_id' => $history->category_id,
            'status' => $history->status
        ]);

        if ($history->tags) {
            $article->tags()->sync($history->tags);
        }

        // Записываем восстановление как новое изменение
        ArticleHistory::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'title' => $history->title,
            'content' => $history->content,
            'category_id' => $history->category_id,
            'status' => $history->status,
            'tags' => $history->tags,
            'changes' => ['type' => 'restored', 'version_id' => $history->id]
        ]);

        return redirect()->back()->with('success', 'Version restored successfully.');
    }
}