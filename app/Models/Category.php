<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;

// class Category extends Model
// {
//     use HasFactory;

//     protected $fillable = ['name', 'slug', 'description', 'parent_id'];

//     public function parent(): BelongsTo
//     {
//         return $this->belongsTo(Category::class, 'parent_id');
//     }

//     public function children(): HasMany
//     {
//         return $this->hasMany(Category::class, 'parent_id');
//     }

//     public function articles(): HasMany
//     {
//         return $this->hasMany(Article::class);
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Получает все дочерние категории рекурсивно
     */
    public function getAllChildren()
    {
        $children = $this->children;
        
        foreach ($children as $child) {
            $children = $children->merge($child->getAllChildren());
        }
        
        return $children;
    }

    /**
     * Получает общее количество статей в категории и всех дочерних категориях
     */
    public function getTotalArticlesCount()
    {
        $count = $this->articles->count();
        
        foreach ($this->children as $child) {
            $count += $child->getTotalArticlesCount();
        }
        
        return $count;
    }
}