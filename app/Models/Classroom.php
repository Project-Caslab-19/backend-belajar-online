<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $guarded = [];
    
    public function topics()
    {
        return $this->hasMany(Topic::class, 'class_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id', 'id');
    }
    
    public function members()
    {
        return $this->hasMany(ClassMember::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
