<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{

    public $table = "filters";

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
