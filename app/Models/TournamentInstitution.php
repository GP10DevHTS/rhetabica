<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentInstitution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tournament_id',
        'institution_id',
        'uuid',  // slug/unique identifier
        'name_override',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }

            if(Auth::check())
                $model->user_id = Auth::id();
        });
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class)->withTrashed();
    }
}
