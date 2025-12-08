<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_login_at',
        'last_login_ip',
        'status',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Relasi dengan Perusahaan (User memiliki satu perusahaan)
     */
    public function perusahaan(): HasOne
    {
        return $this->hasOne(Perusahaan::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is perusahaan
     */
    public function isPerusahaan(): bool
    {
        return $this->role === 'perusahaan';
    }

    /**
     * Check if user has valid perusahaan
     */
    public function hasValidPerusahaan(): bool
    {
        return $this->isPerusahaan() && $this->perusahaan !== null;
    }

    /**
     * Activate user
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Update last login info
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    // Tambahkan di dalam class User

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    public function getUnreadNotificationCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }
}
