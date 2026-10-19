<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\SiteController;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Testimonial;

class HomeController extends SiteController
{
    public function index()
    {
        // Ambil SEMUA section dari database (sorted by order)
        $sections = $this->getAllSections('home');

        // Ambil data pendukung untuk section tertentu
        $portfolios = Portfolio::where('site_id', $this->site->id)
                               ->where('is_published', true)
                               ->where('is_featured', true)
                               ->limit($sections['portfolio']['content']['limit'] ?? 6)
                               ->get();

        $testimonials = Testimonial::where('site_id', $this->site->id)
                                   ->where('is_published', true)
                                   ->where('is_featured', true)
                                   ->limit($sections['testimonials']['content']['limit'] ?? 3)
                                   ->get();

        $latestPosts = BlogPost::where('site_id', $this->site->id)
                               ->where('status', 'published')
                               ->where('published_at', '<=', now())
                               ->orderBy('published_at', 'desc')
                               ->limit(3)
                               ->get();

        $faqs = Faq::where('site_id', $this->site->id)
                   ->where('is_published', true)
                   ->orderBy('order')
                   ->limit($sections['faq']['content']['limit'] ?? 5)
                   ->get();

        return view('sites.company.home', array_merge(
            $this->seoMeta(),
            compact(
                'sections',
                'portfolios',
                'testimonials',
                'latestPosts',
                'faqs'
            )
        ));
    }
}