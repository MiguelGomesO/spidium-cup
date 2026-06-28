<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#060C30">

        <title>{{ config('app.name', 'Spidium Cup') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-brand-ice antialiased bg-brand-black min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-brand-purple via-brand-blue to-brand-black px-4">
            <div>
                <a href="/">
                    <img src="{{ asset('images/ld.png') }}" alt="Spidium Cup" class="w-20 h-20 rounded-full object-cover border border-brand-ice/20">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-brand-surface/80 backdrop-blur-xl border border-brand-ice/10 shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <x-site-footer class="mt-8" />
        </div>
    </body>
</html>
