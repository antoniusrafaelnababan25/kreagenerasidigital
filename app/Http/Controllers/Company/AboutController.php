<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\SiteController;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageSection;

class AboutController extends SiteController
{
    public function index()
    {
        // 1. Ambil halaman 'tentang' (about) dari database
        $page = Page::where('site_id', $this->site->id)
            ->where('slug', 'tentang')
            ->where('is_published', 1)
            ->first();

        // 2. Ambil section yang aktif (misal: hero, content, team)
        $sections = [];
        if ($page) {
            $sections = PageSection::where('page_id', $page->id)
                ->where('is_active', 1)
                ->orderBy('order', 'asc')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->type => $item];
                })
                ->toArray();
        }

        // 3. Ambil data settings untuk kebutuhan Navbar (WhatsApp, dll)
        $siteSettings = \App\Models\Setting::where('site_id', $this->site->id)
            ->pluck('value', 'key')
            ->toArray();

        // 4. Ambil data theme (untuk logo & warna)
        $theme = \App\Models\Theme::where('site_id', $this->site->id)->first();

        // 5. SEO Meta
        $seo = $this->seoMeta([
            'metaTitle' => $page->meta_title ?? 'Tentang Kami | ' . $this->site->name,
            'metaDescription' => $page->meta_description ?? 'Kenali lebih dekat ' . $this->site->name . ', tim profesional di balik kesuksesan digital bisnis Anda.',
        ]);

        // 6. Gabungkan semua data dan kirim ke view
        return view('sites.company.about', array_merge(
            $seo,
            [
                'page' => $page,
                'sections' => $sections,
                'siteSettings' => $siteSettings,
                'theme' => $theme,
            ]
        ));
    }
}