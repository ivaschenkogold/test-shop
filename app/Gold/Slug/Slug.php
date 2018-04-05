<?php
/**
 * Created by PhpStorm.
 * User: offic
 * Date: 12.03.2018
 * Time: 17:52
 */

namespace App\Gold\Slug;

abstract class Slug
{
    public $slug;

    public function __construct($instance, $user_input)
    {
       $this->slug = static::make($instance, $user_input);
    }

   /* public static function getMaker($instance, $slug)
    {
        if ($instance->id === NULL) {
            return new NewSlugMaker($instance->getTable(), $instance->name, $slug, $instance->id);
        } else {
            return new OldSlugMaker($instance->getTable(), $instance->name, $slug, $instance->id);
        }
    }*/

    protected abstract static function make($instance, $user_input);
}