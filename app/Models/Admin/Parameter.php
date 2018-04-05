<?php

namespace App\Models\Admin;

use App\Models\Admin\Good\Good;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Good::class);
    }
}
