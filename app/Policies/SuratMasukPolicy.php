<?php

namespace App\Policies;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuratMasukPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any surat masuk.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff', 'pegawai']);
    }

    /**
     * Determine whether the user can view the surat masuk.
     */
    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        // Super admin and tata usaha can view all
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Kepala dinas can view all
        if ($user->hasRole(['kepala_dinas'])) {
            return true;
        }

        // Kepala bidang can view surat to their bidang
        if ($user->hasRole(['kepala_bidang']) && $user->bidang_id && $suratMasuk->bidang_tujuan_id === $user->bidang_id) {
            return true;
        }

        // Staff and pegawai can view if they created it or it's to their bidang
        if ($user->hasRole(['staff', 'pegawai'])) {
            if ($suratMasuk->diinput_oleh === $user->id) {
                return true;
            }
            if ($user->bidang_id && $suratMasuk->bidang_tujuan_id === $user->bidang_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create surat masuk.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff', 'pegawai']);
    }

    /**
     * Determine whether the user can update the surat masuk.
     */
    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        // Super admin and tata usaha can update all
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Creator can update their own (if not yet disposed)
        if ($suratMasuk->diinput_oleh === $user->id && $suratMasuk->status_disposisi === 'belum') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the surat masuk.
     */
    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        // Only super admin and tata usaha can delete
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Creator can delete their own (if not yet disposed)
        if ($suratMasuk->diinput_oleh === $user->id && $suratMasuk->status_disposisi === 'belum') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create disposisi.
     */
    public function disposisi(User $user, SuratMasuk $suratMasuk): bool
    {
        // Super admin, tata usaha, kepala dinas, kepala bidang can create disposisi
        if ($user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can change status disposisi.
     */
    public function changeStatusDisposisi(User $user, SuratMasuk $suratMasuk): bool
    {
        // Super admin, tata usaha can change status
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Kepala dinas and kepala bidang can change status for their bidang
        if ($user->hasRole(['kepala_dinas', 'kepala_bidang'])) {
            return true;
        }

        return false;
    }
}