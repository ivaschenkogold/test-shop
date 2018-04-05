<?php

namespace App\Models\Admin\Good;

use App\Models\Admin\Parameter;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Category;
use App\Models\Admin\Good\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use SoftDeletes;

    public $table = 'goods';

    public $slugMaker;

    protected $fillable = [
        'title', 'desc', 'keywords', 'slug', 'name', 'text', 'category_id'
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class);
    }
}
