<?php

use App\Http\Controllers\Company;
use App\Http\Controllers\Printing;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rental;
use App\Http\Controllers\Video;
use App\Http\Controllers\WebsiteService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KREA Multi-Site Routing (Path-Based)
|--------------------------------------------------------------------------
|
| Setiap "site" punya prefix path sendiri:
|   /                    → Company Profile
|   /layanan/website     → Jasa Website
|   /layanan/rental      → Rental Alat Camping
|   /layanan/video       → Videografi
|   /layanan/printing    → Custom Printing
|
| Admin panel (Filament) otomatis di: /admin
|
*/

// ═══════════════════════════════════════════════════════════
// COMPANY PROFILE (ROOT)
// ═══════════════════════════════════════════════════════════

Route::name('company.')->group(function () {
    Route::get('/', [Company\HomeController::class, 'index'])->name('home');
    Route::get('/tentang', [Company\AboutController::class, 'index'])->name('about');

    // Portfolio
    Route::get('/portfolio', [Company\PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/{slug}', [Company\PortfolioController::class, 'show'])->name('portfolio.detail');

    // Blog
    Route::get('/blog', [Company\BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{slug}', [Company\BlogController::class, 'show'])->name('blog.detail');

    // Kontak
    Route::get('/kontak', [Company\ContactController::class, 'index'])->name('contact');
    Route::post('/kontak', [Company\ContactController::class, 'store'])->name('contact.store');
});

// ═══════════════════════════════════════════════════════════
// LAYANAN GROUP
// ═══════════════════════════════════════════════════════════

Route::prefix('layanan')->name('services.')->group(function () {

    // ─── JASA WEBSITE ───
    Route::prefix('website')->name('website.')->group(function () {
        Route::get('/', [WebsiteService\HomeController::class, 'index'])->name('home');

        // Paket
        Route::get('/paket', [WebsiteService\PackageController::class, 'index'])->name('package');
        Route::get('/paket/{slug}', [WebsiteService\PackageController::class, 'show'])->name('package.detail');

        // Portfolio
        Route::get('/portfolio', [WebsiteService\PortfolioController::class, 'index'])->name('portfolio');
        Route::get('/portfolio/{slug}', [WebsiteService\PortfolioController::class, 'show'])->name('portfolio.detail');

        // Order
        Route::get('/order', [WebsiteService\OrderController::class, 'create'])->name('order');
        Route::post('/order', [WebsiteService\OrderController::class, 'store'])->name('order.store');
    });

    // ─── RENTAL ALAT CAMPING ───
    Route::prefix('rental')->name('rental.')->group(function () {
        Route::get('/', [Rental\HomeController::class, 'index'])->name('home');

        // Katalog
        Route::get('/katalog', [Rental\CatalogController::class, 'index'])->name('catalog');
        Route::get('/alat/{slug}', [Rental\ItemController::class, 'show'])->name('item');

        // Booking
        Route::get('/booking', [Rental\BookingController::class, 'create'])->name('booking');
        Route::post('/booking', [Rental\BookingController::class, 'store'])->name('booking.store');
        Route::post('/cek-ketersediaan', [Rental\BookingController::class, 'checkAvailability'])->name('check-availability');
    });

    // ─── VIDEOGRAFI ───
    Route::prefix('video')->name('video.')->group(function () {
        Route::get('/', [Video\HomeController::class, 'index'])->name('home');

        // Paket
        Route::get('/paket', [Video\PackageController::class, 'index'])->name('package');
        Route::get('/paket/{slug}', [Video\PackageController::class, 'show'])->name('package.detail');

        // Portfolio
        Route::get('/portfolio', [Video\PortfolioController::class, 'index'])->name('portfolio');
        Route::get('/portfolio/{slug}', [Video\PortfolioController::class, 'show'])->name('portfolio.detail');

        // Order
        Route::get('/order', [Video\OrderController::class, 'create'])->name('order');
        Route::post('/order', [Video\OrderController::class, 'store'])->name('order.store');
    });

    // ─── CUSTOM PRINTING ───
    Route::prefix('printing')->name('printing.')->group(function () {
        Route::get('/', [Printing\HomeController::class, 'index'])->name('home');

        // Produk
        Route::get('/produk', [Printing\ProductController::class, 'index'])->name('product');
        Route::get('/produk/{slug}', [Printing\ProductDetailController::class, 'show'])->name('product.detail');

        // Order
        Route::get('/order', [Printing\OrderController::class, 'create'])->name('order');
        Route::post('/order', [Printing\OrderController::class, 'store'])->name('order.store');
        Route::post('/hitung-harga', [Printing\OrderController::class, 'calculatePrice'])->name('calculate-price');
    });
});

// ═══════════════════════════════════════════════════════════
// BREEZE AUTH ROUTES
// ═══════════════════════════════════════════════════════════

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';