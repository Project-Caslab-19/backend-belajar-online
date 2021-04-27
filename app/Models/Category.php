<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    
    public function classroom()
    {
        return $this->hasMany(Classroom::class, 'category_id', 'id');
    }
}
