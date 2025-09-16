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
        'invited_at',
        'invited_by',
        'confirmed_at',
        'confirmed_by',
        'arrived_at',
        'arrived_recorded_by',
        'invitation_notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }

            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function institution()
    {
        return $this->belongsTo(Institution::class)->withTrashed();
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function arrivedRecordedBy()
    {
        return $this->belongsTo(User::class, 'arrived_recorded_by');
    }

    public function getDisplayNameAttribute()
    {
        return $this->name_override ?: ($this->institution ? $this->institution->name : 'Unknown Institution');
    }

    public function getNameAttribute()
    {
        if ($this->name_override && $this->institution) {
            return "{$this->name_override} ({$this->institution->name})";
        }

        return $this->name_override ?: ($this->institution ? $this->institution->name : 'Unknown Institution');
    }

}
