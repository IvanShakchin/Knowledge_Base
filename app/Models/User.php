<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_approved', // Добавлено поле для подтверждения
        'role', // Добавлено поле для роли
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean', // Добавлено
        ];
    }

    // Проверка, является ли пользователь администратором
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    // Проверка, является ли пользователь суперадминистратором
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Проверка, подтвержден ли пользователь
    public function isApproved()
    {
        return $this->is_approved;
    }
}