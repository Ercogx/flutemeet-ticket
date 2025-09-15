<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class RetrieveWpSettings
{
    public function handle(string $optionName, bool $isEditor = false): string
    {
        $option = DB::table(DB::raw('QamWN8zM_options'))
            ->where('option_name', 'l_event_options')
            ->value('option_value');

        $value = unserialize($option)[$optionName] ?? '';

        if (!$isEditor) {
            return $value;
        }

        return Str::autop(Purifier::clean($value, 'default'));
    }
}
