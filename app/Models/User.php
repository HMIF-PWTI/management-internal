<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'foto',
    'nama',
    'nim',
    'name',
    'email',
    'password',
    'tanggal_lahir',
    'alamat',
    'id_divisi',
    'status_keanggotaan',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function getNameAttribute($value): string
    {
        return $value
            ?? $this->attributes['nama']
            ?? $this->attributes['email']
            ?? 'User';
    }

    public function getFilamentName(): string
    {
        return $this->nama
            ?? $this->email
            ?? 'User';
    }

    /**
     * Relasi user dengan divisi.
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(
            Divisi::class,
            'id_divisi'
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_lahir' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Mengecek apakah user mempunyai akses membaca suatu fitur.
     *
     * Method lama ini tetap dipertahankan agar kode lama
     * yang menggunakan canAccessFitur() tidak error.
     */
    public function canAccessFitur(string $resource): bool
    {
        $this->loadMissing('divisi');

        if (!$this->divisi) {
            return false;
        }

        if (
            strtolower(trim($this->divisi->singkatan))
            === 'superadmin'
        ) {
            return true;
        }

        return $this->divisi
            ->fiturs()
            ->where('fiturs.resource', $resource)
            ->exists();
    }

    /**
     * Mengecek izin CRUD berdasarkan resource dan action.
     *
     * Contoh:
     * hasPermission('users', 'read')
     * hasPermission('users', 'create')
     * hasPermission('users', 'update')
     * hasPermission('users', 'delete')
     */
    public function hasPermission(
        string $resource,
        string $action
    ): bool {
        $this->loadMissing('divisi');

        if (!$this->divisi) {
            return false;
        }

        // Superadmin mempunyai seluruh izin.
        if (
            strtolower(trim($this->divisi->singkatan))
            === 'superadmin'
        ) {
            return true;
        }

        $permissionColumns = [
            'create' => 'can_create',
            'read' => 'can_read',
            'view' => 'can_read',
            'update' => 'can_update',
            'edit' => 'can_update',
            'delete' => 'can_delete',
        ];

        $permissionColumn = $permissionColumns[
            strtolower($action)
        ] ?? null;

        if (!$permissionColumn) {
            return false;
        }

        $fitur = $this->divisi
            ->fiturs()
            ->where('resource', $resource)
            ->first();

        if (!$fitur) {
            return false;
        }

        return (bool) $fitur->pivot->{$permissionColumn};
    }

    public function canCreateFitur(string $resource): bool
    {
        return $this->hasPermission($resource, 'create');
    }

    public function canReadFitur(string $resource): bool
    {
        return $this->hasPermission($resource, 'read');
    }

    public function canUpdateFitur(string $resource): bool
    {
        return $this->hasPermission($resource, 'update');
    }

    public function canDeleteFitur(string $resource): bool
    {
        return $this->hasPermission($resource, 'delete');
    }

    /**
     * User hanya bisa login ke Filament jika status_keanggotaan aktif.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return strtolower(trim((string) $this->status_keanggotaan)) === 'aktif';
    }
    public static function canViewAny(): bool
{
    /** @var User|null $user */
    $user = Auth::user();

    return $user?->canAccessFitur('Info KP') ?? false;
}
}