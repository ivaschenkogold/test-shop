<?php

namespace App\Models\Admin;

use App\Models\Admin\Good\Good;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    public $table = 'categories';

    public $slugMaker;

    protected $fillable = [
        'title', 'desc', 'keywords', 'slug', 'name', 'text'
    ];

    public function goods()
    {
        return $this->hasMany(Good::class);
    }

    public function getCategories()
    {
        return $this->orderBy('_lft')->where('id', '<>', 1)->withDepth()->get();
    }

    public function filters()
    {
        return $this->hasMany(Filter::class);
    }
}
