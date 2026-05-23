<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = ['season', 'status', 'nextinspection_date', 'opened_by', 'closed_by'];

    public static function currentString(): string
    {
        $year = date('Y');
        return $year . '/' . ($year + 1);
    }

    public static function current(): ?Season
    {
        return static::where('season', static::currentString())->first();
    }

    public static function isOpen(): bool
    {
        $season = static::current();
        return $season !== null && $season->status === 'OPEN';
    }
}
