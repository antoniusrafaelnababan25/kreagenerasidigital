<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'font_family',
        'heading_font',
        'hero_gradient',
        'logo_path',
        'favicon_path',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}