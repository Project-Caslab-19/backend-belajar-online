<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];
    
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function topic()
    {
        return $this->hasOne(Topic::class, 'topic_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id', 'id');
    }

    public function quiz_result()
    {
        return $this->hasOne(QuizResult::class, 'quiz_id', 'id');
    }
}
