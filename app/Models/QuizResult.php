<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizResult extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'quiz_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
