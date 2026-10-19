<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\SiteController;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\VideoPackage;

class HomeController extends SiteController
{
    public function index()
    {
        $hero = $this->getSection('home', 'hero');
        $services = $this->getSection('home', 'services');
        $ctaSection = $this->getSection('home', 'cta');

        $packages = VideoPackage::where('site_id', $this->site->id)
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->limit(3)
                                ->get();

        $portfolios = Portfolio::where('site_id', $this->site->id)
                               ->where('is_published', true)
                               ->whereIn('service_type', ['video', 'multi'])
                               ->orderBy('order')
                               ->limit(6)
                               ->get();

        $testimonials = Testimonial::where('site_id', $this->site->id)
                                   ->where('is_published', true)
                                   ->whereIn('service_type', ['video', 'multi'])
                                   ->limit(3)
                                   ->get();

        return view('sites.video.home', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Videografi Profesional | KREA',
                'metaDescription' => 'Jasa videografi profesional untuk wedding, company profile, event, dan konten sosial media.',
            ]),
            compact('hero', 'services', 'ctaSection', 'packages', 'portfolios', 'testimonials')
        ));
    }
}