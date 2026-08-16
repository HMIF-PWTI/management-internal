<?php

namespace App\Providers;

use App\Models\Kegiatan;
use App\Observers\KegiatanObserver;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Factory;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Database::class, function (): Database {
            $credentials = config('firebase.credentials');
            $databaseUrl = config('firebase.database_url');

            if (! is_string($credentials) || $credentials === '' || ! is_string($databaseUrl) || $databaseUrl === '') {
                throw new RuntimeException('Konfigurasi Firebase belum lengkap.');
            }

            $credentialsPath = $this->resolveCredentialsPath($credentials);

            if (! is_file($credentialsPath)) {
                throw new RuntimeException('File credential Firebase tidak ditemukan.');
            }

            return (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUrl)
                ->createDatabase();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Kegiatan::observe(KegiatanObserver::class);
    }

    private function resolveCredentialsPath(string $path): string
    {
        if (preg_match('/^(?:[A-Za-z]:[\\\\\/]|[\\\\\/])/', $path) === 1) {
            return $path;
        }

        return base_path($path);
    }
}
