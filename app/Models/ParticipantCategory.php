<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ParticipantCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'uuid',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::slug($model->name);
            }
        });
    }

    /**
     * The user who created this category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Optional: Debaters in this category
     */
    public function debaters()
    {
        return $this->hasMany(TournamentDebater::class, 'debater_category_id');
    }
}
