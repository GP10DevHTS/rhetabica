<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeakerRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'abbreviation',
        'order',
    ];
}
