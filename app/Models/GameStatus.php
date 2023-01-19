<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\models\QuestionStatus;

class GameStatus extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;
    
    protected $fillable = [
        'host_id',
        'host_name',
        'host_color',
        'host_current_score',
        'host_current_wrong_score',
        'host_current_bonus_score',
        'host_current_total_score',
        'host_score',
        'host_wrong_score',
        'host_bonus_score',
        'host_total_score',
        'join_id',
        'join_name',
        'join_color',
        'join_current_score',
        'join_current_wrong_score',
        'join_current_bonus_score',
        'join_current_total_score',
        'join_score',
        'join_wrong_score',
        'join_bonus_score',
        'join_total_score',
        'room_number',
        'question_category_id',
        'current_round',
        'selected_option',
        'current_player',
        'status',
    ];
    
    public function questionStatus()
    {
        return $this->hasOne(QuestionStatus::class, 'game_status_id');
    }
}
