<?php
namespace App\Traits;
//use Illuminate\Support\Facades\Auth;

trait InterfaceTrait{

    public function __get($name)
    {
        if ($name == 'interface') {
            if (method_exists($this,'getInterface')) {
                return $this->getInterface();
            }else{
                return 'admin';
            }
        }

    }

}