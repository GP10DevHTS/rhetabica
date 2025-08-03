<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Tabspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'context',
        'slug',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(Auth::check())
                $model->user_id = Auth::id();

            $baseSlug = Str::slug($model->name);
            $slug = $baseSlug;
            $attempts = 0;

            while (static::where('slug', $slug)->exists()) {
                $attempts++;

                if ($attempts > 5) {
                    $slug = $baseSlug . '-' . uniqid();
                } else {
                    $slug = $baseSlug . '-' . Str::lower(Str::random(4));
                }
            }

            $model->slug = $slug;
        });
    }
}
