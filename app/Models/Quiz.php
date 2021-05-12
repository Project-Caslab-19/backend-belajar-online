<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;
    
    protected $table = 'quiz';
    protected $guarded = [];
    
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function topic()
    {
        return $this->hasOne(Topic::class, 'id', 'topic_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id', 'id');
    }

    public function quiz_results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id', 'id');
    }
}
