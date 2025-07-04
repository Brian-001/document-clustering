<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $fillable = ['name', 'path', 'match_percentage', 'matched_keywords', 'group'];

    protected $casts = [
        'matched_keywords' => 'array',
    ];
}
