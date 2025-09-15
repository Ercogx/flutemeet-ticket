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
            if (trim($text) === '') {
                return '';
            }

            // Protect <pre>, <script>, <style>, <textarea> blocks (do not transform their internals)
            $preserve = [];
            if (preg_match_all('#<(pre|script|style|textarea)(\b[^>]*)>(.*?)</\1>#is', $text, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $i => $m) {
                    $key = "___PRESERVE_BLOCK_{$i}___";
                    $preserve[$key] = $m[0];
                    $text = str_replace($m[0], $key, $text);
                }
            }

            // Normalize line endings
            $text = str_replace(["\r\n", "\r"], "\n", $text);

            // Block-level tags we must NOT wrap in <p>
            $block_tags = 'address|article|aside|blockquote|canvas|dd|div|dl|dt|fieldset|figcaption|figure|footer|form|h[1-6]|header|hgroup|hr|li|main|nav|noscript|ol|output|p|pre|section|table|tfoot|ul|video';

            // Ensure block tags are on their own lines (add newlines around them)
            // This makes splitting into paragraphs easier.
            $text = preg_replace_callback(
                '#<(\/?(' . $block_tags . ')(?:\b[^>]*)?)>#i',
                function ($m) { return "\n" . $m[0] . "\n"; },
                $text
            );

            // Collapse multiple newlines to exactly two (paragraph boundary)
            $text = preg_replace("/\n{2,}/", "\n\n", trim($text));

            // Split into "paragraph" chunks by double newlines
            $parts = preg_split('/\n\s*\n/', $text);

            $out = '';
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === '') {
                    continue;
                }

                // If the chunk starts with a block tag or ends with one,
                // don't wrap it in <p>. This prevents <p><h2>..</h2></p>.
                if (preg_match('#^<(?:' . $block_tags . ')(?:\b[^>]*)?>#i', $part)
                    || preg_match('#</(?:' . $block_tags . ')>\s*$#i', $part)
                ) {
                    $out .= $part . "\n";
                } else {
                    // Convert single newlines to <br />\n inside paragraphs
                    $part = preg_replace("/\n/", "<br />\n", $part);
                    $out .= '<p>' . $part . "</p>\n";
                }
            }

            // Remove accidental <p> wrapped around block tags (safety pass)
            $out = preg_replace(
                '#<p>\s*(</?(?:' . $block_tags . ')(?:\b[^>]*)?>)\s*</p>#i',
                '$1',
                $out
            );

            // Restore preserved blocks
            if (!empty($preserve)) {
                foreach ($preserve as $key => $orig) {
                    $out = str_replace($key, $orig, $out);
                }
            }

            return trim($out);
        });
    }
}
