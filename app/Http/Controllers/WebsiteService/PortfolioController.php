<?php

namespace App\Http\Controllers\WebsiteService;

use App\Http\Controllers\SiteController;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function index(Request $request)
    {
        $query = Portfolio::where('site_id', $this->site->id)
                          ->where('is_published', true)
                          ->whereIn('service_type', ['website', 'multi']);

        if ($request->filled('year')) {
            $query->where('project_year', $request->year);
        }

        $portfolios = $query->orderBy('order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        $years = Portfolio::where('site_id', $this->site->id)
                          ->where('is_published', true)
                          ->whereIn('service_type', ['website', 'multi'])
                          ->distinct()
                          ->pluck('project_year')
                          ->filter()
                          ->sortDesc();

        // Ambil hero dari halaman portfolio
        $hero = $this->getSection('portfolio', 'hero');

        return view('sites.website-service.portfolio', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Portfolio Website | KREA',
                'metaDescription' => 'Lihat portfolio website yang telah kami kerjakan untuk berbagai klien.',
            ]),
            compact('portfolios', 'years', 'hero')
        ));
    }

    public function show(string $slug)
    {
        $portfolio = Portfolio::where('site_id', $this->site->id)
                              ->where('slug', $slug)
                              ->where('is_published', true)
                              ->firstOrFail();

        $portfolio->increment('view_count');

        $related = Portfolio::where('site_id', $this->site->id)
                            ->where('is_published', true)
                            ->whereIn('service_type', ['website', 'multi'])
                            ->where('id', '!=', $portfolio->id)
                            ->limit(3)
                            ->get();

        // Ambil hero dari halaman portfolio
        $hero = $this->getSection('portfolio', 'hero');

        return view('sites.website-service.portfolio-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $portfolio->meta_title ?? $portfolio->title . ' | KREA',
                'metaDescription' => $portfolio->meta_description ?? $portfolio->short_description,
            ]),
            compact('portfolio', 'related', 'hero')
        ));
    }
}