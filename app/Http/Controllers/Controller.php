<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    //
    public function index()
    {
        return User::all();
    }
}
