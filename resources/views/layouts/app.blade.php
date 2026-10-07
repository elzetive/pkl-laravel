<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Manajemen Akademik') }}</title>

        @include('layouts.include.css')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            @include('layouts.include.navbar')

            @auth
                @if (auth()->user()->peran === 'A')
                    @include('layouts.include.admin_sidebar')
                @elseif (auth()->user()->peran === 'D')
                    @include('layouts.include.dosen_sidebar')
                @elseif (auth()->user()->peran === 'M')
                    @include('layouts.include.mahasiswa_sidebar')
                @endif
            @endauth
            <div class="content-wrapper">
                @yield('content')
            </div>
            @include('layouts.include.footer')
        </div>
        @include('layouts.include.script')
        @stack('script')
    </body>
</html>
