<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $guarded = [];

    public function answer(){
        return $this->hasOne(Answer::class, 'answer_id', 'id');
    }

    public function question(){
        return $this->hasOne(Question::class, 'question_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
