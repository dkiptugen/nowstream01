{{-- CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Basic SEO --}}
<meta name="description" content="@yield('description', config('site.description'))">
<meta name="keywords" content="@yield('keywords', config('site.keywords'))">
<meta name="author" content="{{ config('site.name') }}">
<meta name="copyright" content="{{ config('site.name') }}">
<meta name="application-name" content="@yield('title', config('site.title'))">

{{-- Canonical --}}
<link rel="canonical" href="{{ request()->fullUrl() }}">

{{-- Favicon --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:title" content="@yield('title', config('site.title'))">
<meta property="og:description" content="@yield('description', config('site.description'))">
<meta property="og:image" content="@yield('image', config('site.image'))">
<meta property="og:locale" content="en_GB">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ config('site.twitter_handle') }}">
<meta name="twitter:title" content="@yield('title', config('site.title'))">
<meta name="twitter:description" content="@yield('description', config('site.description'))">
<meta name="twitter:image" content="@yield('image', config('site.image'))">
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FT13EMDEPD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FT13EMDEPD');
</script>
@include('Frontend.includes.components.partials.audio-player')