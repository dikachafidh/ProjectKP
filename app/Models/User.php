<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama', 'username', 'email', 'password',
        'role', 'jabatan', 'aktif', 'last_login',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'last_login' => 'datetime',
        'aktif'      => 'boolean',
    ];

    // Override identifier ke username
    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    // ============================================================
    // ROLE LABELS & BADGES
    // ============================================================

    public function getLabelRoleAttribute(): string
    {
        return match($this->role) {
            'admin'  => 'Administrator',
            'kepsek' => 'Kepala Sekolah',
            'staff'  => 'Staff',
            default  => ucfirst($this->role),
        };
    }

    public function getBadgeRoleAttribute(): string
    {
        return match($this->role) {
            'admin'  => '<span class="badge bg-danger">Admin</span>',
            'kepsek' => '<span class="badge bg-warning text-dark">Kepsek</span>',
            'staff'  => '<span class="badge bg-secondary">Staff</span>',
            default  => '<span class="badge bg-secondary">'.$this->role.'</span>',
        };
    }

    public function getIconRoleAttribute(): string
    {
        return match($this->role) {
            'admin'  => '👑',
            'kepsek' => '🏫',
            'staff'  => '👤',
            default  => '👤',
        };
    }

    // ============================================================
    // PERMISSION CHECKS
    // Admin  : semua akses (tambah, edit, hapus, cetak, kelola user)
    // Kepsek : lihat saja (baca semua data & laporan)
    // Staff  : lihat saja (baca semua data & laporan)
    // ============================================================

    /** Bisa menambah data baru */
    public function canCreate(): bool
    {
        return $this->role === 'admin';
    }

    /** Bisa mengedit data */
    public function canEdit(): bool
    {
        return $this->role === 'admin';
    }

    /** Bisa menghapus data */
    public function canDelete(): bool
    {
        return $this->role === 'admin';
    }

    /** Bisa cetak QR Code / barcode */
    public function canPrint(): bool
    {
        return $this->role === 'admin';
    }

    /** Bisa kelola user (tambah/edit/hapus user) */
    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }

    /** Hanya bisa lihat (view only) */
    public function isViewOnly(): bool
    {
        return in_array($this->role, ['kepsek', 'staff']);
    }

    /** Cek apakah admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
