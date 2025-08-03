<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tabspace_id',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tournament) {
            $tournament->slug = Str::slug($tournament->name);
        });
    }

    public function tabspace()
    {
        return $this->belongsTo(Tabspace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
