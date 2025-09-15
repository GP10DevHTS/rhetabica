<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TournamentRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'nickname',
        'uuid',
        'slug',
        'description',
        'user_id',
        'tournament_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($room) {
            // Set the authenticated user if not already set
            if (Auth::check() && !$room->user_id) {
                $room->user_id = Auth::id();
            }

            // Generate UUID if not set
            // if (!$room->uuid) {
                $room->uuid = (string) Str::uuid();
            // }

            // Generate a unique slug per tournament
            $baseSlug = Str::slug($room->name);
            $slug = $baseSlug;
            $counter = 1;

            while (
                static::where('tournament_id', $room->tournament_id)
                      ->where('slug', $slug)
                      ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $room->slug = $slug;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
