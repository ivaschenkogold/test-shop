<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Module;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class Role extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function funcs()
    {
        return $this->belongsToMany(Func::class);
    }

    //Проверяет имеет ли данный пользователь доступ к функции модуля
    protected function hasAccess($module, $func)
    {
        if (Auth::user()->role_id != 1) {
            $access = Auth::user()->role->whereHas('funcs', function ($query) use ($func, $module) {
                $query->where("slug", $func)->whereHas('module', function ($query) use ($module) {
                    $query->where("slug", $module);
                });
            })->first();

            if ($access === null) {
                return false;
            }
        }

        return true;
    }

    //Возвращает доступа для текущего модуля и всех дефолтных модуей и их функций
    // для залогиненого пользователя
    protected function getModuleAccesses($module)
    {
        $return_accesses = [];
        if (Auth::user()->role_id != 1) {
            $accesses = Auth::user()->role->funcs()->whereHas('module', function ($query) use ($module) {
                $query->where('slug', $module);
            })->get();

            $accesses_arr = [];
            if ($accesses) {
                foreach ($accesses as $access) {
                    $accesses_arr[$access->slug] = 1;
                }
            }
            $return_accesses[$module] = (Object)$accesses_arr;

            $config_accesses = config('accesses');
            if ($config_accesses) {
                if (isset($config_accesses[$module])) {
                    unset($config_accesses[$module]);
                }
                if ($config_accesses) {
                    foreach ($config_accesses as $def_module => $def_functions) {
                        $def_accesses = Auth()->user()->role->funcs()->whereIn('slug', $def_functions)
                            ->whereHas('module', function ($query) use ($def_module) {
                                $query->where('slug', $def_module);
                            })->get();

                        if ($def_accesses) {
                            $def_accesses_arr = [];
                            foreach ($def_accesses as $def_access) {
                                $def_accesses_arr[$def_access->slug] = 1;
                            }
                        }
                        $return_accesses[$def_module] = (Object)$def_accesses_arr;
                    }
                }
            }
        } else {
            $accesses = Module::where('slug', $module)->first();
            $accesses_arr = [];
            if ($accesses) {
                foreach ($accesses->funcs as $access) {
                    $accesses_arr[$access->slug] = 1;
                }
            }
            $return_accesses[$module] = (Object)$accesses_arr;

            $config_accesses = config('accesses');
            if ($config_accesses) {
                if (isset($config_accesses[$module])) {
                    unset($config_accesses[$module]);
                }
                if ($config_accesses) {
                    foreach ($config_accesses as $def_module => $def_functions) {
                        $def_accesses_arr = [];
                        if ($def_functions) {
                            foreach ($def_functions as $def_function) {
                                $def_accesses_arr[$def_function] = 1;
                            }
                        }
                        $return_accesses[$def_module] = (Object)$def_accesses_arr;
                    }
                }
            }
        }

        $return_accesses = (Object)$return_accesses;

        return $return_accesses;
    }

    public function getWithoutAdmin()
    {
        return $this->where('id', '<>', 1)->get();
    }
}
