<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionCategory extends Pivot
{
    protected $fillable = [
        'question_id',
        'category_id',
    ];

    protected $table = 'category_question';
}
