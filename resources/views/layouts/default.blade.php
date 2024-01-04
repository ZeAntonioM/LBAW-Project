<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Project Planer') }}</title>
        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-oqVyuhRYq0hM5GZ8f5KfQcR5F7L3p1pA5Zpx3rxHTi5F3sHtfzyv+gZep6PKd6hQ" crossorigin="anonymous">


        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/default.js') }} defer>
        </script>

        @stack('styles')
        <!-- Scripts -->
        <script type="module" src={{ url('js/app.js') }} defer></script>
        @stack('scripts')

    </head>

    <body>

    <main>
        <section id="content">
            @yield('content')
        </section>
    </main>

    </body>
</html>