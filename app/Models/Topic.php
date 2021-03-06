<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'id', 'class_id');
    }

    public function learnings()
    {
        return $this->hasMany(Learning::class, 'topic_id', 'id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'topic_id', 'id');
    }
}
