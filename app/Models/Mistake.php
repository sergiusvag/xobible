<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\Question;

class Mistake extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $fillable = [
        'question_id',
        'mistake',
        'author_id'
    ];
    /**
     * Get the post that owns the comment.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
