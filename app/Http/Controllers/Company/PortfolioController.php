<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\SiteController;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function index(Request $request)
    {
        // ═══ AMBIL SECTIONS DARI DATABASE ═══
        $sections = $this->getAllSections('portfolio');

        // ═══ QUERY PORTFOLIO ═══
        $query = Portfolio::where('site_id', $this->site->id)
                          ->where('is_published', true);

        // Filter by service type
        if ($request->filled('service')) {
            $query->where('service_type', $request->service);
        }

        $portfolios = $query->orderBy('order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        $services = Portfolio::where('site_id', $this->site->id)
                             ->where('is_published', true)
                             ->distinct()
                             ->pluck('service_type')
                             ->filter();

        return view('sites.company.portfolio', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Portfolio | ' . $this->site->name,
                'metaDescription' => 'Lihat karya-karya terbaik KREA Generasi Digital.',
            ]),
            compact('sections', 'portfolios', 'services')
        ));
    }

    public function show(string $slug)
    {
        // ═══ AMBIL SECTIONS DARI DATABASE ═══
        $sections = $this->getAllSections('portfolio');

        // ═══ QUERY PORTFOLIO ═══
        $portfolio = Portfolio::where('site_id', $this->site->id)
                              ->where('slug', $slug)
                              ->where('is_published', true)
                              ->firstOrFail();

        $portfolio->increment('view_count');

        $related = Portfolio::where('site_id', $this->site->id)
                            ->where('is_published', true)
                            ->where('id', '!=', $portfolio->id)
                            ->where('service_type', $portfolio->service_type)
                            ->limit(3)
                            ->get();

        return view('sites.company.portfolio-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $portfolio->meta_title ?? $portfolio->title . ' | KREA',
                'metaDescription' => $portfolio->meta_description ?? $portfolio->short_description,
            ]),
            compact('sections', 'portfolio', 'related')
        ));
    }
}