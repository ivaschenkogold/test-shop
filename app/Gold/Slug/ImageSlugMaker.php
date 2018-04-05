<?php
/**
 * Created by PhpStorm.
 * User: offic
 * Date: 16.03.2018
 * Time: 11:36
 */

namespace App\Gold\Slug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageSlugMaker extends Slug
{
    protected static function make($instance, $user_input)
    {
        $slug = $slug = Str::slug($user_input, '-');
        $full_name = $slug . "." . $instance->extention;
        $isset = DB::table($instance->table)->where('slug', $full_name)->first();
        if (!$isset) {
            $isset = file_exists(public_path('images/' . $full_name));
        }

        if ($isset) {
            $i = 1;
            $tmp_slug = $slug;
            do {
                $full_name = $tmp_slug . "-" . $i . "." . $instance->extention;
                $isset = DB::table($instance->table)->where('slug', $slug)->first();
                if (!$isset) {
                    $isset = file_exists(public_path('images/' . $full_name));
                }
                $i++;
            } while ($isset);
        }

        return $full_name;
    }
}