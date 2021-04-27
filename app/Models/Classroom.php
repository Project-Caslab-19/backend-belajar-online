<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'classroom';
    
    public function topics()
    {
        return $this->hasMany(Topic::class, 'class_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    
    public function members()
    {
        return $this->hasMany(ClassMember::class, 'class_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
