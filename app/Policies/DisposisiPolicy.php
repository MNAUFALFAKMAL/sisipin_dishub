<?php

namespace App\Policies;

use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DisposisiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any disposisi.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang']);
    }

    /**
     * Determine whether the user can view the disposisi.
     */
    public function view(User $user, Disposisi $disposisi): bool
    {
        // Super admin can view all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // User yang terlibat: creator, penerima, atau super_admin
        if ($disposisi->dari_user_id === $user->id || $disposisi->kepada_user_id === $user->id) {
            return true;
        }

        // Kepala bidang bisa lihat disposisi ke bidangnya
        if ($user->hasRole('kepala_bidang') && $user->bidang_id && $disposisi->kepada_bidang_id === $user->bidang_id) {
            return true;
        }

        // Tata usaha bisa lihat semua
        if ($user->hasRole('tata_usaha')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create disposisi.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang']);
    }

    /**
     * Determine whether the user can update the disposisi.
     * Hanya creator (disposisi_oleh) yang bisa edit jika status pending (baru)
     */
    public function update(User $user, Disposisi $disposisi): bool
    {
        // Super admin can update all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Creator bisa edit jika status baru
        if ($disposisi->dari_user_id === $user->id && $disposisi->status === 'baru') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the disposisi.
     * Hanya creator yang bisa hapus jika status pending (baru)
     */
    public function delete(User $user, Disposisi $disposisi): bool
    {
        // Super admin can delete all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Creator bisa hapus jika status baru
        if ($disposisi->dari_user_id === $user->id && $disposisi->status === 'baru') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can proses the disposisi.
     * Hanya user yang menerima disposisi (kepada_user_id)
     */
    public function proses(User $user, Disposisi $disposisi): bool
    {
        // Super admin can proses all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Penerima disposisi bisa proses
        if ($disposisi->kepada_user_id === $user->id) {
            return true;
        }

        // Kepala bidang bisa proses untuk bidangnya
        if ($user->hasRole('kepala_bidang') && $user->bidang_id && $disposisi->kepada_bidang_id === $user->bidang_id) {
            return true;
        }

        // Tata usaha bisa proses
        if ($user->hasRole('tata_usaha')) {
            return true;
        }

        return false;
    }
}