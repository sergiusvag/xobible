<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\GameStatus;
use App\models\Question;

class QuestionStatus extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $fillable = [
        'room_number',
        'round_number',
        '0_field_question_id',
        '0_field_question_status',
        '1_field_question_id',
        '1_field_question_status',
        '2_field_question_id',
        '2_field_question_status',
        '3_field_question_id',
        '3_field_question_status',
        '4_field_question_id',
        '4_field_question_status',
        '5_field_question_id',
        '5_field_question_status',
        '6_field_question_id',
        '6_field_question_status',
        '7_field_question_id',
        '7_field_question_status',
        '8_field_question_id',
        '8_field_question_status',
        'selected_field',
        'game_status_id',
    ];
    
    public function gameStatus()
    {
        return $this->belongsTo(GameStatus::class, 'game_status_id');
    }

    public function question_0()
    {
        return $this->belongsTo(Question::class, '0_field_question_id');
    }

    public function question_1()
    {
        return $this->belongsTo(Question::class, '1_field_question_id');
    }

    public function question_2()
    {
        return $this->belongsTo(Question::class, '2_field_question_id');
    }

    public function question_3()
    {
        return $this->belongsTo(Question::class, '3_field_question_id');
    }

    public function question_4()
    {
        return $this->belongsTo(Question::class, '4_field_question_id');
    }

    public function question_5()
    {
        return $this->belongsTo(Question::class, '5_field_question_id');
    }

    public function question_6()
    {
        return $this->belongsTo(Question::class, '6_field_question_id');
    }

    public function question_7()
    {
        return $this->belongsTo(Question::class, '7_field_question_id');
    }

    public function question_8()
    {
        return $this->belongsTo(Question::class, '8_field_question_id');
    }
}
