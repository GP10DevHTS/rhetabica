<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TournamentTeam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tournament_id',
        'uuid',
        'name',
        'participant_category_id',
        'created_by',
        'tournament_institution_id',
        'slug',
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
            $model->slug = Str::slug($model->name);
        });
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participantCategory()
    {
        return $this->belongsTo(ParticipantCategory::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function institution()
    {
        return $this->belongsTo(TournamentInstitution::class, 'tournament_institution_id');
    }
}
