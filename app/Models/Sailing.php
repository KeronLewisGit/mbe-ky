<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sailing extends Model
{
    protected $fillable = ['cutoff_date', 'sailing_date', 'in_hand_date'];

    protected function casts(): array
    {
        return ['cutoff_date' => 'date', 'sailing_date' => 'date', 'in_hand_date' => 'date'];
    }

    /** Sailings that have not yet arrived on island. */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('in_hand_date', '>=', now(config('mbe.timezone'))->toDateString())->orderBy('sailing_date');
    }

    public function cutoffPassed(): bool
    {
        return $this->cutoff_date->lt(now(config('mbe.timezone'))->startOfDay());
    }

    public function hasSailed(): bool
    {
        return $this->sailing_date->lt(now(config('mbe.timezone'))->startOfDay());
    }
}
