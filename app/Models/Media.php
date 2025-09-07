<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Media extends Model

{

    use HasFactory;



    protected $fillable = [

        'article_id',

        'filename',

        'original_name',

        'mime_type',

        'path',

        'size'

    ];



    public function article(): BelongsTo

    {

        return $this->belongsTo(Article::class);

    }



    public function isImage(): bool

    {

        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif']);

    }



    public function isPdf(): bool

    {

        return $this->mime_type === 'application/pdf';

    }

}