<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class Content
{
    protected static array $icons = [];

    /** E-box FAQ grouped by category: [['category' => ..., 'items' => [['q' => ..., 'a' => html]]]]. */
    public static function faq(): array
    {
        static $faq;

        return $faq ??= json_decode(file_get_contents(database_path('data/faq.json')), true);
    }

    public static function legal(string $slug): ?string
    {
        $path = resource_path("content/legal/{$slug}.html");

        return is_file($path) ? file_get_contents($path) : null;
    }

    /** Inline a Lucide icon from resources/icons. */
    public static function icon(string $name, string $class = 'size-5', string $attributes = ''): string
    {
        if (! isset(self::$icons[$name])) {
            $path = resource_path("icons/{$name}.svg");
            $svg = is_file($path) ? file_get_contents($path) : '<svg viewBox="0 0 24 24"></svg>';
            $svg = preg_replace('/<!--.*?-->/s', '', $svg);
            // Drop the sizing attributes from the root <svg> only, so shapes such as <rect> keep theirs.
            $svg = preg_replace_callback('/<svg\b[^>]*>/', fn (array $tag) => preg_replace('/\s(class|width|height)="[^"]*"/', '', $tag[0]), $svg, 1);
            self::$icons[$name] = trim(preg_replace('/\s+/', ' ', $svg));
        }

        return str_replace('<svg', '<svg class="'.e($class).'" aria-hidden="true" '.$attributes, self::$icons[$name]);
    }

    /** Largest upload the forms accept, in MB: 8 MB, or less if this server's PHP limit is lower. */
    public static function uploadLimitMb(): int
    {
        $limit = ini_parse_quantity(ini_get('upload_max_filesize') ?: '8M');

        return (int) max(1, min(8, floor($limit / 1048576)));
    }

    /** Open/closed state for a store right now, in Cayman time. */
    public static function storeStatus(array $location): array
    {
        $now = Carbon::now(config('mbe.timezone'));
        $today = $location['weekly'][$now->dayOfWeek] ?? null;
        $hour = $now->hour + $now->minute / 60;

        if ($today && $hour >= $today[0] && $hour < $today[1]) {
            return ['open' => true, 'text' => 'Open now · closes '.self::hour($today[1])];
        }

        if ($today && $hour < $today[0]) {
            return ['open' => false, 'text' => 'Closed · opens '.self::hour($today[0]).' today'];
        }

        for ($i = 1; $i <= 7; $i++) {
            $day = $now->copy()->addDays($i);
            if ($next = $location['weekly'][$day->dayOfWeek] ?? null) {
                return ['open' => false, 'text' => 'Closed · opens '.self::hour($next[0]).' '.($i === 1 ? 'tomorrow' : $day->format('l'))];
            }
        }

        return ['open' => false, 'text' => 'Closed'];
    }

    protected static function hour(int $hour): string
    {
        return ($hour % 12 ?: 12).($hour < 12 ? 'am' : 'pm');
    }
}
