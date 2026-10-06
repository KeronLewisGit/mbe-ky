<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['slug', 'title', 'category', 'image', 'body', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'date'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function excerpt(int $length = 170): string
    {
        return Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>'], ' ', $this->body))))), $length);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }
}
