<meta charset="utf-8" />
<title>@yield('title') | CBTMEDIA</title>

<!-- Responsive viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Primary Meta Tags -->
<meta name="title" content="@yield('title') | CBTMEDIA">
<meta name="description" content="Sistem Ujian Online CBTMEDIA - {{ $sekolah?->nama ?? 'Sekolah Anda' }}, platform pendidikan berbasis teknologi untuk mendukung ujian online yang modern dan efisien.">
<meta name="keywords" content="CBTMEDIA, ujian online, CBT, {{ $sekolah?->nama ?? 'Sekolah' }}, pendidikan, aplikasi ujian, e-learning">
<meta name="subject" content="Situs Pendidikan">
<meta name="author" content="aplikasimedia.com">
<meta name="designer" content="aplikasimedia.com">
<meta name="language" content="id">
<meta name="robots" content="index, follow">
<meta name="rating" content="general">
<meta name="distribution" content="global">
<meta name="coverage" content="Worldwide">
<meta name="classification" content="Education">
{{-- <meta name="slogan" content="Maju Bersama Hebat Semua"> --}}
<meta name="copyright" content="{{ $sekolah?->nama ?? 'CBTMEDIA' }}">
<meta http-equiv="Content-Language" content="id">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Canonical & URL Info -->
@php $baseUrl = request()->getSchemeAndHttpHost(); @endphp
<link rel="canonical" href="{{ $baseUrl }}">
<meta name="url" content="{{ $baseUrl }}">
<meta name="identifier-URL" content="{{ $baseUrl }}">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $baseUrl }}">
<meta property="og:title" content="@yield('title') | CBTMEDIA">
<meta property="og:description" content="Sistem Ujian Online CBTMEDIA - {{ $sekolah?->nama ?? 'Sekolah Anda' }}.">
<meta property="og:image" content="{{ URL::asset('images/aplikasi-media.png') }}">
<meta property="og:site_name" content="CBTMEDIA">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title') | CBTMEDIA">
<meta name="twitter:description" content="Sistem Ujian Online CBTMEDIA - {{ $sekolah?->nama ?? 'Sekolah Anda' }}.">
<meta name="twitter:image" content="{{ URL::asset('images/aplikasi-media.png') }}">
<meta name="twitter:site" content="@CBTMEDIA">

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ URL::asset('images/aplikasi-media.png') }}">
