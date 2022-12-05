<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class QuestionEn extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $table = 'questions_en';

    protected $fillable = [
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'answer',
        'location',
        'confirmed',
        'author_id',
        'question_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}