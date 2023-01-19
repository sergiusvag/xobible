<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $fillable = [
    ];

    
    public function questions()
    {
        return $this->belongsToMany(Question::class)->using(QuestionCategory::class);
    }
    
    public function CategoryEn()
    {
        return $this->hasOne(CategoryEn::class);
    }
    
    public function CategoryRu()
    {
        return $this->hasOne(CategoryRu::class);
    }
    
    public function CategoryHe()
    {
        return $this->hasOne(CategoryHe::class);
    }
}