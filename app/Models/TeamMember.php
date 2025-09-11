<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tournament_team_id',
        'tournament_debater_id',
        'uuid',
        'created_by',
        'role',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (Auth::check() && empty($model->created_by)) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(TournamentTeam::class);
    }

    public function debater()
    {
        return $this->belongsTo(TournamentDebater::class);
    }
}
