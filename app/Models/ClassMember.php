<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassMember extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    
    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'id', 'class_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
