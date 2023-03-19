@php
    use Symfony\Component\HttpKernel\Exception\HttpException;

    /** @var HttpException $exception */
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Error 404</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased">

<main>
    <section class="error">
        <h1>Error 404: Not found</h1>
        <article class="error">{{ $exception->getMessage() }}</article>
    </section>
    <footer>
        Copyright by <a href="https://github.com/olml89">Oleguer Mas</a>
    </footer>
</main>

</body>
</html>

