<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\SiteController;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function index(Request $request)
    {
        $query = Portfolio::where('site_id', $this->site->id)
                          ->where('is_published', true)
                          ->whereIn('service_type', ['video', 'multi']);

        if ($request->filled('year')) {
            $query->where('project_year', $request->year);
        }

        $portfolios = $query->orderBy('order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        $years = Portfolio::where('site_id', $this->site->id)
                          ->where('is_published', true)
                          ->whereIn('service_type', ['video', 'multi'])
                          ->distinct()
                          ->pluck('project_year')
                          ->filter()
                          ->sortDesc();

        // ✅ Tambahkan Hero
        $hero = $this->getSection('portfolio', 'hero');

        return view('sites.video.portfolio', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Portfolio Video | KREA',
                'metaDescription' => 'Lihat portfolio video yang telah kami produksi untuk berbagai klien.',
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
                            ->whereIn('service_type', ['video', 'multi'])
                            ->where('id', '!=', $portfolio->id)
                            ->limit(3)
                            ->get();

        // Schema markup VideoObject
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $portfolio->title,
            'description' => $portfolio->short_description,
            'thumbnailUrl' => $portfolio->featured_image ? asset('storage/' . $portfolio->featured_image) : null,
            'uploadDate' => $portfolio->created_at->toIso8601String(),
        ];

        if ($portfolio->video_url) {
            $schema['contentUrl'] = $portfolio->video_url;
        }

        // ✅ Tambahkan Hero
        $hero = $this->getSection('portfolio', 'hero');

        return view('sites.video.portfolio-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $portfolio->meta_title ?? $portfolio->title . ' | KREA',
                'metaDescription' => $portfolio->meta_description ?? $portfolio->short_description,
                'schema' => $schema,
            ]),
            compact('portfolio', 'related', 'schema', 'hero')
        ));
    }
}