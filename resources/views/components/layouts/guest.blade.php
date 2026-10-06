<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100 font-sans antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Masuk ke Sistem' }} — Lancar Barokah Sablon</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-[#f1f5f9] text-slate-900 antialiased selection:bg-[#0e3f32] selection:text-white flex items-center justify-center p-4 sm:p-6 lg:p-8">

    {{ $slot }}

    @livewireScripts
</body>
</html>
