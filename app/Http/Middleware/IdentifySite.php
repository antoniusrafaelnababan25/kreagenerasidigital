<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifySite
{
    /**
     * Deteksi site berdasarkan path URL.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = trim($request->path(), '/');

        // Cari site berdasarkan path
        $site = $this->resolveSite($path);

        // Fallback ke company (root)
        if (! $site) {
            $site = Site::where('key', 'company')->first();
        }

        if (! $site) {
            abort(500, 'Site belum di-seed. Jalankan: php artisan db:seed');
        }

        // Bind ke container
        app()->instance('currentSite', $site);

        // Share ke view
        view()->share('currentSite', $site);
        view()->share('theme', $site->theme);
        view()->share('siteSettings', $site->settings->pluck('value', 'key'));

        return $next($request);
    }

    /**
     * Resolve site dari path.
     */
    protected function resolveSite(string $path): ?Site
    {
        // Path kosong = company
        if ($path === '' || $path === '/') {
            return Site::where('path', '/')->first();
        }

        // Cek path spesifik (layanan/website, layanan/rental, dst)
        $site = Site::where('path', $path)->first();
        if ($site) {
            return $site;
        }

        // Cek prefix: /layanan/website/... → site website
        foreach (Site::where('path', '!=', '/')->get() as $candidate) {
            if (str_starts_with($path, $candidate->path)) {
                return $candidate;
            }
        }

        return null;
    }
}