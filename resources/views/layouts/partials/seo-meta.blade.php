<title>{{ $metaTitle ?? $currentSite->name ?? 'KREA' }}</title>
<meta name="description" content="{{ $metaDescription ?? '' }}">
<meta property="og:title" content="{{ $metaTitle ?? $currentSite->name ?? 'KREA' }}">
<meta property="og:description" content="{{ $metaDescription ?? '' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">