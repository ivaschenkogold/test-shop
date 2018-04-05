<?php
/**
 * Created by PhpStorm.
 * User: offic
 * Date: 12.03.2018
 * Time: 18:25
 */

namespace App\Gold\Slug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewSlugMaker extends Slug
{
    /*private $table;
    private $name;
    private $slug;
    private $id;

    public function __construct($table, $name, $slug, $id)
    {
        $this->table = $table;
        $this->name = $name;
        $this->slug = $slug;
        $this->id = $id;
    }*/

    protected static function make($instance, $slug)
    {
        if ($slug == '') {
            $slug = Str::slug($instance->name, '-');
        }
        $isset = DB::table($instance->table)->where('slug', $slug)->first();
        if ($isset) {
            $i = 1;
            $tmp_slug = $slug;
            do {
                $slug = $tmp_slug . "-" . $i;
                $isset = DB::table($instance->table)->where('slug', $slug)->first();
                $i++;
            } while ($isset);
        }

        return $slug;
    }
}