<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'path',
        'domain',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Filament Multi-Tenancy ───

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function getFilamentSlug(): string
    {
        return $this->key;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    // ─── Relationships ───

    public function theme()
    {
        return $this->hasOne(Theme::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * FIX: gunakan hasMany karena page_sections punya site_id langsung.
     */
    public function pageSections()
    {
        return $this->hasMany(PageSection::class, 'site_id');
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function blogCategories()
    {
        return $this->hasMany(BlogCategory::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function blogTags()
    {
        return $this->hasMany(BlogTag::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function rentalBookings()
    {
        return $this->hasMany(RentalBooking::class);
    }

    public function printProducts()
    {
        return $this->hasMany(PrintProduct::class);
    }

    public function printOrders()
    {
        return $this->hasMany(PrintOrder::class);
    }

    public function videoPackages()
    {
        return $this->hasMany(VideoPackage::class);
    }

    public function videoOrders()
    {
        return $this->hasMany(VideoOrder::class);
    }

    public function webPackages()
    {
        return $this->hasMany(WebPackage::class);
    }

    public function webOrders()
    {
        return $this->hasMany(WebOrder::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ───

    public function setting(string $key, $default = null)
    {
        return $this->settings->where('key', $key)->first()?->value ?? $default;
    }

    public function hasTheme(): bool
    {
        return $this->theme()->exists();
    }

    public function getPrimaryColorAttribute(): string
    {
        return $this->theme?->primary_color ?? '#3B82F6';
    }
}