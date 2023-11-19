<?php

namespace App\Http\Controllers;

class AdminAppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
