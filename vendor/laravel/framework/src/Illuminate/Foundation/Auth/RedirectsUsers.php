<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Support\Facades\Auth;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /*Метод который перебивает
     свойство redirectTo и
     выполняет редирект после авторизации*/
    public function redirectTo()
    {
        if (Auth::user()->role_id == 0) {
            return '/user';
        }else{
            return '/admin';
        }
    }
}
