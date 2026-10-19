<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Site;
use Illuminate\Support\Collection;

abstract class SiteController extends Controller
{
    /**
     * Site yang sedang aktif.
     */
    protected ?Site $site = null;

    /**
     * Theme site yang aktif.
     */
    protected $theme = null;

    /**
     * Settings site yang aktif.
     */
    protected $settings;

    public function __construct()
    {
        // Ambil site yang sedang aktif dari container
        // (di-set oleh middleware IdentifySite)
        $this->site = app()->bound('currentSite') ? app('currentSite') : null;
        $this->theme = $this->site?->theme;
        $this->settings = $this->site?->settings->pluck('value', 'key') ?? collect();

        // Share ke semua view
        view()->share('currentSite', $this->site);
        view()->share('theme', $this->theme);
        view()->share('siteSettings', $this->settings);
    }

    /**
     * Ambil sections untuk halaman tertentu (di-key berdasarkan tipe).
     */
    protected function getPageSections(string $slug): array
    {
        if (! $this->site) return [];

        $page = Page::where('site_id', $this->site->id)
                    ->where('slug', $slug)
                    ->where('is_published', true)
                    ->first();

        if (! $page) return [];

        return PageSection::where('page_id', $page->id)
                          ->where('is_active', true)
                          ->orderBy('order')
                          ->get()
                          ->keyBy('type')
                          ->toArray();
    }

    /**
     * Ambil SEMUA sections untuk halaman tertentu (berurutan sesuai order).
     * Method ini yang dipanggil oleh Company\HomeController.
     */
    protected function getAllSections(string $slug): Collection
    {
        if (! $this->site) return collect();

        $page = Page::where('site_id', $this->site->id)
                    ->where('slug', $slug)
                    ->where('is_published', true)
                    ->first();

        if (! $page) return collect();

        return PageSection::where('page_id', $page->id)
                          ->where('is_active', true)
                          ->orderBy('order')
                          ->get()
                          ->keyBy('type'); // Return sebagai Collection yang di-keyBy type agar mudah diakses di view
    }

    /**
     * Ambil section by type.
     */
    protected function getSection(string $pageSlug, string $type): ?array
    {
        $sections = $this->getPageSections($pageSlug);
        return $sections[$type] ?? null;
    }

    /**
     * Ambil setting dengan fallback.
     */
    protected function setting(string $key, $default = null)
    {
        return $this->settings->get($key, $default);
    }

    /**
     * SEO meta default.
     */
    protected function seoMeta(array $overrides = []): array
    {
        return array_merge([
            'metaTitle' => $this->setting('meta_title_default', $this->site?->name),
            'metaDescription' => $this->setting('meta_description_default', ''),
            'metaKeywords' => '',
            'ogImage' => null,
            'canonical' => url()->current(),
        ], $overrides);
    }
}