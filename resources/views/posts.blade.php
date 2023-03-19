@php
use olml89\IPGlobalTest\Post\Application\PostResult;

/** @var PostResult[] $posts */
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>List of posts</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased">

<main>
    <section>
        <h1>This is a comprehensive list of posts</h1>

        @foreach ($posts as $post)
            <article class="preview">
                <a class="content" href="/posts/{{ $post->id }}">
                    <div class="title"><strong>{{ $post->title }}</strong></div>
                    <div class="timestamp">posted on: {{ $post->posted_at }}</div>
                </a>
            </article>
        @endforeach
    </section>
    <footer>
        Copyright by <a href="https://github.com/olml89">Oleguer Mas</a>
    </footer>
</main>

</body>
</html>
