<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MacrosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->autop();
    }

    private function autop(): void
    {
        Str::macro('autop', function (string $text) {
            $text = trim($text);

            // Normalize line breaks
            $text = preg_replace("/\r\n|\r/", "\n", $text);

            // Double line breaks → paragraph
            $paragraphs = preg_split('/\n\s*\n/', $text);

            $text = '';
            foreach ($paragraphs as $p) {
                $p = trim($p);
                if ($p) {
                    $p = nl2br($p); // single line break → <br>
                    $text .= "<p>{$p}</p>\n";
                }
            }

            return $text;
        });
    }
}
