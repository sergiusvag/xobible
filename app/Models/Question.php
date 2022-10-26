<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\Mistake;
use App\models\QuestionRu;
use App\models\QuestionHe;
// use App\models\QuestionAr; // Example for new question model in a new langauge

class Question extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $fillable = [
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'answer',
        'location',
        'confirmed',
        'author_id'
    ];

    public function mistakes()
    {
        return $this->hasMany(Mistake::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    public function questionRu()
    {
        return $this->hasOne(QuestionRu::class);
    }
    
    public function questionHe()
    {
        return $this->hasOne(QuestionHe::class);
    }
    
    // Example for a new relationship with the new question model
    // public function questionAr()
    // {
    //     return $this->hasOne(QuestionAr::class);
    // }
}
