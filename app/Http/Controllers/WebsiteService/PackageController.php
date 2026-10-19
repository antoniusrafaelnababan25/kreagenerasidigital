<?php

namespace App\Http\Controllers\WebsiteService;

use App\Http\Controllers\SiteController;
use App\Models\WebPackage;
use Illuminate\Http\Request;

class PackageController extends SiteController
{
    public function index(Request $request)
    {
        $query = WebPackage::where('site_id', $this->site->id)
                           ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $packages = $query->orderBy('order')->get();

        $categories = WebPackage::where('site_id', $this->site->id)
                                ->where('is_active', true)
                                ->distinct()
                                ->pluck('category')
                                ->filter();

        // ✅ PERBAIKAN: Gunakan slug 'paket' agar mengambil hero dari halaman Paket & Harga
        $hero = $this->getSection('paket', 'hero');

        return view('sites.website-service.package', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Paket & Harga Jasa Website | KREA',
                'metaDescription' => 'Pilih paket website sesuai kebutuhan bisnis Anda. Harga transparan, kualitas terjamin.',
            ]),
            compact('packages', 'categories', 'hero')
        ));
    }

    public function show(string $slug)
    {
        $package = WebPackage::where('site_id', $this->site->id)
                             ->where('slug', $slug)
                             ->where('is_active', true)
                             ->firstOrFail();

        $related = WebPackage::where('site_id', $this->site->id)
                             ->where('is_active', true)
                             ->where('id', '!=', $package->id)
                             ->where('category', $package->category)
                             ->limit(3)
                             ->get();

        // ✅ PERBAIKAN: Gunakan slug 'paket' agar mengambil hero dari halaman Paket & Harga
        $hero = $this->getSection('paket', 'hero');

        return view('sites.website-service.package-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $package->name . ' | KREA',
                'metaDescription' => $package->description,
            ]),
            compact('package', 'related', 'hero')
        ));
    }
}