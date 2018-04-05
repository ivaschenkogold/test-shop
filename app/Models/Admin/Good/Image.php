<?php

namespace App\Models\Admin\Good;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Good\Good;

class Image extends Model
{
    public $table = 'good_images';

    public $slugMaker;

    public $extention;

    public function good()
    {
        return $this->belongsTo(Good::class);
    }
}
