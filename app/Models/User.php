<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;
use App\models\Question;
use App\models\Mistake;
use App\models\Suggestion;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'author_id');
    }
    public function mistakes()
    {
        return $this->hasMany(Mistake::class, 'author_id');
    }
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class, 'author_id');
    }
}
