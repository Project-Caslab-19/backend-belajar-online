<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Learning extends Model
{
    protected $guarded = [];

    public function topic()
    {
        return $this->hasOne(Topic::class, 'topic_id', 'id');
    }
}
