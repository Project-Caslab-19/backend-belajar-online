<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    
    public function question()
    {
        return $this->hasOne(Question::class, 'question_id', 'id');
    }
}
