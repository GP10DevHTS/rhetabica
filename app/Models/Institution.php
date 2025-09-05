<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country',
        'city',
        'website',
        'logo_path',
        'uuid',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::slug($model->name);
        });
    }

    /**
     * Each institution belongs to a user (creator/manager).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * Debaters from this institution.
    //  */
    // public function debaters()
    // {
    //     return $this->hasMany(Debater::class);
    // }

    // /**
    //  * Adjudicators from this institution.
    //  */
    // public function adjudicators()
    // {
    //     return $this->hasMany(Adjudicator::class);
    // }

    // /**
    //  * Tabmasters from this institution.
    //  */
    // public function tabmasters()
    // {
    //     return $this->hasMany(Tabmaster::class);
    // }

    // /**
    //  * Patrons from this institution.
    //  */
    // public function patrons()
    // {
    //     return $this->hasMany(Patron::class);
    // }
}
