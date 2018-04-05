<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function funcs()
    {
        return $this->hasMany(Func::class);
    }
}
