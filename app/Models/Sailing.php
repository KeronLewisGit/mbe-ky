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
        return $query->whereDate('in_hand_date', '>=', self::today())->orderBy('sailing_date');
    }

    /** The cutoff stays open for the whole of its day in Cayman time. */
    public function cutoffPassed(): bool
    {
        return $this->cutoff_date->toDateString() < self::today();
    }

    /** A sailing counts as sailed once its day in Cayman time is over. */
    public function hasSailed(): bool
    {
        return $this->sailing_date->toDateString() < self::today();
    }

    /** Today's date in Cayman time. Dates are compared as strings because the app timezone is UTC. */
    protected static function today(): string
    {
        return now(config('mbe.timezone'))->toDateString();
    }
}
