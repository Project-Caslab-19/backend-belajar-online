<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    
    public function classroom()
    {
        return $this->hasMany(Classroom::class, 'category_id', 'id');
    }
}
