<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    const TYPE_MOVIE = 1;
    const TYPE_SHOW = 2;

    const AVAILABLE_TYPES = [
        self::TYPE_MOVIE,
        self::TYPE_SHOW,
    ];
}
