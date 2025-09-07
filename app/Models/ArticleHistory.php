<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'title',
        'content',
        'category_id',
        'status',
        'tags',
        'changes'
    ];

    protected $casts = [
        'tags' => 'array',
        'changes' => 'array'
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}