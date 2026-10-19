<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\SiteController;
use App\Models\VideoPackage;
use Illuminate\Http\Request;

class PackageController extends SiteController
{
    public function index(Request $request)
    {
        $query = VideoPackage::where('site_id', $this->site->id)
                              ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $packages = $query->orderBy('order')->get();

        $categories = VideoPackage::where('site_id', $this->site->id)
                                  ->where('is_active', true)
                                  ->distinct()
                                  ->pluck('category')
                                  ->filter();

        // ✅ Tambahkan Hero
        $hero = $this->getSection('paket', 'hero');

        return view('sites.video.package', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Paket Videografi | KREA',
                'metaDescription' => 'Pilih paket videografi sesuai kebutuhan Anda. Wedding, corporate, event, dan konten sosmed.',
            ]),
            compact('packages', 'categories', 'hero')
        ));
    }

    public function show(string $slug)
    {
        $package = VideoPackage::where('site_id', $this->site->id)
                               ->where('slug', $slug)
                               ->where('is_active', true)
                               ->firstOrFail();

        $related = VideoPackage::where('site_id', $this->site->id)
                               ->where('is_active', true)
                               ->where('id', '!=', $package->id)
                               ->where('category', $package->category)
                               ->limit(3)
                               ->get();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $package->name,
            'description' => $package->description,
            'offers' => [
                '@type' => 'Offer',
                'price' => $package->price,
                'priceCurrency' => 'IDR',
            ],
        ];

        // ✅ Tambahkan Hero
        $hero = $this->getSection('paket', 'hero');

        return view('sites.video.package-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $package->name . ' | KREA Video',
                'metaDescription' => $package->description,
                'schema' => $schema,
            ]),
            compact('package', 'related', 'schema', 'hero')
        ));
    }
}