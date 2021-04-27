<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];

    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'class_id', 'id');
    }

    public function learning()
    {
        return $this->hasOne(Learning::class, 'topic_id', 'id');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'topic_id', 'id');
    }
}
