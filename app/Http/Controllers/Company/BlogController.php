<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\SiteController;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends SiteController
{
    public function index(Request $request)
    {
        // ═══ AMBIL SECTIONS DARI DATABASE ═══
        $sections = $this->getAllSections('blog');

        // ═══ QUERY POSTS ═══
        $query = BlogPost::where('site_id', $this->site->id)
                         ->where('status', 'published')
                         ->where('published_at', '<=', now());

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(9);

        $categories = BlogCategory::where('site_id', $this->site->id)
                                  ->where('is_active', true)
                                  ->orderBy('order')
                                  ->get();

        $featured = BlogPost::where('site_id', $this->site->id)
                            ->where('status', 'published')
                            ->where('is_featured', true)
                            ->orderBy('published_at', 'desc')
                            ->limit(3)
                            ->get();

        return view('sites.company.blog', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Blog & Artikel | ' . $this->site->name,
                'metaDescription' => 'Baca artikel, tips, dan berita terbaru dari KREA.',
            ]),
            compact('sections', 'posts', 'categories', 'featured')
        ));
    }

    public function show(string $slug)
    {
        // ═══ AMBIL SECTIONS DARI DATABASE ═══
        $sections = $this->getAllSections('blog');

        // ═══ QUERY POST ═══
        $post = BlogPost::where('site_id', $this->site->id)
                        ->where('slug', $slug)
                        ->where('status', 'published')
                        ->where('published_at', '<=', now())
                        ->firstOrFail();

        $post->increment('view_count');

        $related = BlogPost::where('site_id', $this->site->id)
                           ->where('status', 'published')
                           ->where('id', '!=', $post->id)
                           ->where('blog_category_id', $post->blog_category_id)
                           ->limit(3)
                           ->get();

        // Schema markup untuk artikel
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->featured_image,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author?->name ?? 'KREA',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'KREA Generasi Digital',
            ],
        ];

        return view('sites.company.blog-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $post->meta_title ?? $post->title . ' | KREA',
                'metaDescription' => $post->meta_description ?? $post->excerpt,
                'ogImage' => $post->og_image,
                'schema' => $schema,
            ]),
            compact('sections', 'post', 'related', 'schema')
        ));
    }
}