<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationActivity;
use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use App\Models\User;
use App\Policies\DisposisiPolicy;
use App\Policies\SuratKeluarPolicy;
use App\Policies\SuratMasukPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');

        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(SuratMasuk::class, SuratMasukPolicy::class);
        Gate::policy(SuratKeluar::class, SuratKeluarPolicy::class);
        Gate::policy(Disposisi::class, DisposisiPolicy::class);

        // Catat aktivitas login/logout ke audit log
        Event::listen(Login::class, LogAuthenticationActivity::class);
        Event::listen(Logout::class, LogAuthenticationActivity::class);

        // Daftar tahun untuk dropdown navigasi sidebar (Surat Menyurat → Tahun)
        View::composer('layouts.sidebar', function ($view) {
            $isSqlite = DB::connection()->getDriverName() === 'sqlite';
            $yearExpr = $isSqlite
                ? "CAST(strftime('%Y', COALESCE(tanggal_terima, created_at)) AS INTEGER)"
                : 'YEAR(COALESCE(tanggal_terima, created_at))';

            $tahunDariTabel = TahunArsip::query()->pluck('tahun');
            $tahunDariMasuk = SuratMasuk::query()
                ->selectRaw("DISTINCT {$yearExpr} AS tahun")
                ->pluck('tahun');
            $tahunDariKeluar = SuratKeluar::query()
                ->selectRaw("DISTINCT {$yearExpr} AS tahun")
                ->pluck('tahun');

            $tahunList = collect($tahunDariTabel)
                ->merge($tahunDariMasuk)
                ->merge($tahunDariKeluar)
                ->filter(fn ($t) => $t !== null && $t !== '')
                ->map(fn ($t) => (int) $t)
                ->unique()
                ->sortDesc()
                ->values();

            $view->with('tahunArsipList', $tahunList);

            // Tahun yang benar-benar tersimpan di tabel tahun_arsips
            // (hanya tahun ini yang boleh dihapus dari navigasi)
            $view->with('tahunArsipTersimpan', collect($tahunDariTabel)
                ->map(fn ($t) => (int) $t)
                ->unique()
                ->values());
        });
    }
}
