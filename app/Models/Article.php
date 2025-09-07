<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'slug', 'content', 'category_id', 'author_id', 'status'];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_PUBLISHED_AUTH = 'published_auth';
    const STATUS_PENDING = 'pending';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category->name,
        ];
    }
    
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ArticleHistory::class);
    }

    public function scopeVisibleTo($query, $user = null)
    {
        $isAuthenticated = !is_null($user);
        
        if ($isAuthenticated) {
            return $query->whereIn('status', [self::STATUS_PUBLISHED, self::STATUS_PUBLISHED_AUTH]);
        } else {
            return $query->where('status', self::STATUS_PUBLISHED);
        }
    }
}