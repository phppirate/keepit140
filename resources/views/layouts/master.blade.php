<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Keep It 140</title>

        <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="pb-12">
        <header class="container px-4 mx-auto pb-6 pt-2">
            <div class="row border-b border-grey">
                <div class="py-6 flex-y-center justify-between">
                    <a href="/" class="flex-y-center">
                        Keep It 140
                    </a>
                </div>
            </div>
        </header>

        <div class="container px-4 mx-auto pt-4 content">
            @yield('content')
        </div>
    </body>
</html>
