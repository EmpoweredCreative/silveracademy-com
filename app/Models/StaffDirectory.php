<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDirectory extends Model
{
    protected $table = 'staff_directory';

    protected $fillable = [
        'name',
        'title',
        'department',
        'photo',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
