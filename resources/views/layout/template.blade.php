<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tiMovie - @yield('title', 'Website')</title>
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>

    @include('layout.navbar')

    <div class="container my-2">
        @yield('content')
    </div>

    @include('layout.footer')

    <script src="/bootstrap/bootstrap.bundle.min.js"></script>
  </body>
</html>
