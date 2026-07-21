<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'ruc',
        'email',
        'logo',
        'activo',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'config' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function supportAgents()
    {
        return $this->belongsToMany(User::class, 'support_assignments');
    }
}