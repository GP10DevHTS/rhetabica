<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tabspace_id',
        'user_id',
        'is_public',
    ];

    public function scopeVisibleTo($query, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user || (!$user->is_admin && $user->id !== $this->user_id)) {
            $query->where('is_public', true);
        }

        if ($user && !$user->is_admin) {
            $query->where(function($q) use ($user) {
                $q->where('is_public', true)
                  ->orWhere('user_id', $user->id);
            });
        }

        return $query;
    }

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

    public function institutions()
    {
        return $this->hasMany(TournamentInstitution::class, 'tournament_id');
    }

    public function participants()
    {
        // new members in the system
        return $this->hasMany(TournamentParticipant::class);
    }

    public function judges()
    {
        return $this->hasMany(TournamentJudge::class);
    }

    public function tabMasters()
    {
        return $this->hasMany(TournamentTabMaster::class);
    }
    public function patrons()
    {
        return $this->hasMany(TournamentPatron::class);
    }
    public function debaters()
    {
        // actual debters for the whole tournament
        return $this->hasMany(TournamentDebater::class);
    }

    // teams
    public function teams(){
        return $this->hasMany(TournamentTeam::class);
    }

    public function participantCategories()
    {
        return ParticipantCategory::whereIn(
            'id',
            $this->debaters()->pluck('participant_category_id')->filter()->unique()
        );
    }

     public function participantInstitutions()
    {
        return Institution::whereIn(
            'id',
            $this->institutions()
                ->whereNotNull('arrived_at')
                ->pluck('institution_id')->filter()->unique()
        );
    }

}

