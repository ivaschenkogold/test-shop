<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Func extends Model
{
    protected $table = 'functions';

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
