<?php

namespace App\Http\Controllers;

use App\Models\Event;

class WhiteListController extends Controller
{
    public function show(Event $event)
    {
        return view('whitelist-show', compact('event'));
    }
}
