<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use App\models\User;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'host_id',
        'host_name',
        'join_id',
        'join_name',
        'room_number',
        'room_key',
        'status',
    ];
    
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function join()
    {
        return $this->belongsTo(User::class, 'join_id');
    }
}
