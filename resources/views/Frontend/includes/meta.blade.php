<meta name="csrf-token" content="{{ csrf_token() }}" />

<meta name="description" content="@yield('description', config('site.description'))" />
<meta name="keywords" content="@yield('keywords', config('site.keywords'))" />
<meta name="copyright" content="{{ config('site.name') }}">
<meta name="author" content="{{ config('site.name') }}" />
<meta name="application-name" content="@yield('title', config('site.title'))">

<!--Facebook Tags-->
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:type" content="article" />
<meta property="og:url" content="{{ request()->fullUrl() }}" />
<meta property="og:title" content="@yield('title', config('site.title'))" />
<meta property="og:description" content="@yield('description', config('site.description'))" />
<meta property="og:image" content="{{ config('site.image') }}" />
<meta property="article:author" content="https://www.facebook.com/TODO" />
<meta property="og:locale" content="en_UK" />
<!--Twitter Tags-->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="{{  config('site.twitter_handle') }}" />
<meta name="twitter:title" content="@yield('title', config('site.name'))" />
<meta name="twitter:description" content="@yield('description', config('site.description'))" />
<meta name="twitter:image" content="{{ config('site.image') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('Frontend.includes.components.partials.audio-player')