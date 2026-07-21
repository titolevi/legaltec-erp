<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'telefono',
    'rol',
    'activo',
    'tenant_id',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'preferencias',
    'last_login_at',
    'last_login_ip',
])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'activo' => 'boolean',
            'preferencias' => 'array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // TenantScope NO se aplica a User (evita bucle infinito)
    }

    // ─── Relaciones ───────────────────────────────────────

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function ticketsCreados()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    public function ticketsPorAutorizar()
    {
        return $this->hasMany(Ticket::class, 'autorizador_id');
    }

    public function tenantsAsignados()
    {
        return $this->belongsToMany(Tenant::class, 'support_assignments')
                    ->withTimestamps();
    }

    // ─── Helper: Rol ──────────────────────────────────────

    public function esSuperAdmin(): bool
    {
        return $this->rol === 'super_admin';
    }

    public function esSupport(): bool
    {
        return in_array($this->rol, ['support_admin', 'support_agent']);
    }

    public function esAdminTenant(): bool
    {
        return $this->rol === 'admin';
    }

    public function esLegaltec(): bool
    {
        return is_null($this->tenant_id);
    }

    // ─── Helper: 2FA ──────────────────────────────────────

    public function tiene2faActivo(): bool
    {
        return !is_null($this->two_factor_confirmed_at);
    }

    // ─── Helper: Preferencias ─────────────────────────────

    public function getTemaAttribute(): string
    {
        return $this->preferencias['tema'] ?? 'claro';
    }

    public function setPreferencia(string $key, mixed $value): void
    {
        $prefs = $this->preferencias ?? [];
        $prefs[$key] = $value;
        $this->update(['preferencias' => $prefs]);
    }
}