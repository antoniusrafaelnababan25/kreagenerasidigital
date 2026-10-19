<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\SiteController;
use App\Models\Lead;
use Illuminate\Http\Request;

class ContactController extends SiteController
{
    public function index()
    {
        // ═══ AMBIL SECTIONS DARI DATABASE ═══
        // Mengambil semua section (termasuk hero) khusus untuk halaman 'kontak'
        $sections = $this->getAllSections('kontak');

        return view('sites.company.contact', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Kontak | ' . $this->site->name,
                'metaDescription' => 'Hubungi KREA Generasi Digital untuk konsultasi gratis.',
            ]),
            compact('sections')
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'service_type' => 'nullable|string',
            'message' => 'required|string',
        ]);

        $validated['site_id'] = $this->site->id;
        $validated['source'] = 'website';
        $validated['status'] = 'new';
        $validated['priority'] = 'medium';

        Lead::create($validated);

        return redirect()
            ->route('company.contact')
            ->with('success', 'Terima kasih! Pesan Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
    }
}