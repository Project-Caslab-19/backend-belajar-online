<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learning extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function topic()
    {
        return $this->hasOne(Topic::class, 'topic_id', 'id');
    }
}
