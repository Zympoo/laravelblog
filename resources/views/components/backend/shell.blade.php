<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1,
shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $title ?? 'Dashboard - SB Admin' }}</title>
    @vite(['resources/css/styles.css', 'resources/js/app.js'])
    {{-- Named slot: extra <head> tags per pagina (optioneel) --}}
    {{ $head ?? '' }}
</head>
<body class="sb-nav-fixed">
<x-backend.topnav />
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <x-backend.sidenav />
    </div>
    <div id="layoutSidenav_content">
        <main>
            {{ $slot }}
        </main>
        <x-backend.footer />
    </div>
</div>
{{-- Named slot: extra scripts per pagina (optioneel) --}}
{{ $scripts ?? '' }}
</body>
</html>
