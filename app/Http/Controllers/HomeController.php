<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'event' => Event::whereIsActive(true)->latest()->first()
        ]);
    }
}
