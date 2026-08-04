<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class maps extends Model
{
    // No columns beyond id/timestamps are defined yet; guard id against mass-assignment
    // once attributes are added so new columns default to fillable, not the primary key.
    protected $guarded = ['id'];
}
