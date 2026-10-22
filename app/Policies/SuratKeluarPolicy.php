<?php

namespace App\Policies;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuratKeluarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any surat keluar.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff', 'pegawai']);
    }

    /**
     * Determine whether the user can view the surat keluar.
     */
    public function view(User $user, SuratKeluar $suratKeluar): bool
    {
        // Super admin and tata usaha can view all
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Kepala dinas can view all
        if ($user->hasRole(['kepala_dinas'])) {
            return true;
        }

        // Kepala bidang can view surat from their bidang
        if ($user->hasRole(['kepala_bidang']) && $user->bidang_id && $suratKeluar->bidang_id === $user->bidang_id) {
            return true;
        }

        // Staff and pegawai can view if they created it or it's from their bidang
        if ($user->hasRole(['staff', 'pegawai'])) {
            if ($suratKeluar->dibuat_oleh === $user->id) {
                return true;
            }
            if ($user->bidang_id && $suratKeluar->bidang_id === $user->bidang_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create surat keluar.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff', 'pegawai']);
    }

    /**
     * Determine whether the user can update the surat keluar.
     */
    public function update(User $user, SuratKeluar $suratKeluar): bool
    {
        // Super admin and tata usaha can update all
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Creator can update their own (if status is draft)
        if ($suratKeluar->dibuat_oleh === $user->id && $suratKeluar->status === 'draft') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the surat keluar.
     */
    public function delete(User $user, SuratKeluar $suratKeluar): bool
    {
        // Only super admin and tata usaha can delete
        if ($user->hasRole(['super_admin', 'tata_usaha'])) {
            return true;
        }

        // Creator can delete their own (if status is draft)
        if ($suratKeluar->dibuat_oleh === $user->id && $suratKeluar->status === 'draft') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can change status.
     */
    public function changeStatus(User $user, SuratKeluar $suratKeluar): bool
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