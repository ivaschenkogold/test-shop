<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\InterfaceTrait;

class AdminController extends Controller
{
    use InterfaceTrait;

    public function index(Request $request)
    {
        return view($this->interface.'.index');
    }
}
