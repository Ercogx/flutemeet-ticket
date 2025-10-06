<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WhitelistNotify;
use App\Models\EventWhitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailWhitelistController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:event_whitelists,id']);

        EventWhitelist::findMany($request->collect('ids'))->each(function (EventWhitelist $whitelist, int $index) {
            Mail::send((new WhitelistNotify($whitelist))->delay($index * 15));
        });

        return response()->json(['message' => 'Email sent successfully']);
    }
}
