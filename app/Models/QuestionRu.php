<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\Question;

class QuestionRu extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions_ru';

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

    public function questionEn()
    {
        return $this->belongsTo(Question::class);
    }
    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
