<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMember extends Model
{
    protected $guarded = [];
    
    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'class_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
