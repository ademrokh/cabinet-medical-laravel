<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800&family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3 { font-family: 'Sora', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-orange-50 text-slate-900">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-10">
            <div class="w-full max-w-md">
                <a href="/" class="flex items-center gap-3 mb-8">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 text-white flex items-center justify-center font-bold shadow-lg">CM</div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-500 font-semibold">Cabinet</p>
                        <p class="text-xl font-bold">Portail patient</p>
                    </div>
                </a>

                <div class="relative">
                    <div class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-emerald-200 blur-3xl opacity-70"></div>
                    <div class="absolute -bottom-6 -left-6 h-24 w-24 rounded-full bg-orange-200 blur-3xl opacity-70"></div>
                    <div class="relative bg-white/90 backdrop-blur rounded-3xl shadow-2xl px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-6 text-center text-sm text-slate-500">Votre espace medical securise.</p>
            </div>
        </div>
    </body>
</html>
