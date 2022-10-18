<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\Mistake;

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
        'location'
    ];
    
    public function mistakes()
    {
        return $this->hasMany(Mistake::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
