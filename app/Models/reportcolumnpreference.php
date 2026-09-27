<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reportcolumnpreference extends Model
{
    protected $table = 'report_column_preferences';

    protected $fillable = ['user_id', 'report_type', 'columns'];

    protected $casts = [
        'columns' => 'array',
    ];
}
