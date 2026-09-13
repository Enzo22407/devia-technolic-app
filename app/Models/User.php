<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'matricule',
        'role',
        'filiere',
        'groupe',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function studentRequests()
    {
        return $this->hasMany(StudentRequest::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function isResponsablePedagogique(): bool
    {
        return $this->role === 'responsable_pedagogique';
    }

    public function isAdminSysteme(): bool
    {
        return $this->role === 'admin_systeme';
    }
}
