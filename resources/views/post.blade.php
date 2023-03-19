@php
    use olml89\IPGlobalTest\Post\Application\PostResult;

    /** @var PostResult $post */
@endphp


    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $post->title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased">

<main>
    <section class="post">
        <h1>{{ $post->title }}</h1>

        <article class="post" data-id="{{ $post->id }}">
            <div class="meta">Posted by <strong>{{ $post->user->username }}</strong> on {{ $post->posted_at }}</div>
            <div class="body">{{ $post->body }}</div>
        </article>

        <aside class="user" data-id="{{ $post->user->id }}">
            <h2>About {{ $post->user->username }}</h2>
            <div class="info">
                <div class="personal">
                    <h3>Contact data</h3>
                    <table>
                        <tr><th>Name:</th><td>{{ $post->user->name }}</td></tr>
                        <tr><th>Email:</th><td>{{ $post->user->email }}</td></tr>
                        <tr><th>Phone:</th><td>{{ $post->user->phone }}</td></tr>
                        <tr><th>Website:</th><td><a href="{{ $post->user->website }}">{{ $post->user->website }}</a><</td></tr>
                    </table>
                </div>
                <div class="address">
                    <h3>Address</h3>
                    <table>
                        <tr><th>Street:</th><td>{{ $post->user->address->street }}</td></tr>
                        <tr><th>Suite:</th><td>{{ $post->user->address->suite }}</td></tr>
                        <tr><th>City:</th><td>{{ $post->user->address->city }}</td></tr>
                        <tr><th>Zip code:</th><td>{{ $post->user->address->zipCode }}</td></tr>
                        <tr>
                            <th>Geolocation:</th>
                            <td>
                                ({{ $post->user->address->geoLocation->latitude }},
                                {{ $post->user->address->geoLocation->longitude}})
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="company">
                    <h3>Company</h3>
                    <table>
                        <tr><th>Name:</th><td>{{ $post->user->company->name }}</td></tr>
                        <tr><th>Catchphrase:</th><td>{{ $post->user->company->catchphrase }}</td></tr>
                        <tr><th>Bs:</th><td>{{ $post->user->company->bs }}</td></tr>
                    </table>
                </div>
            </div>
        </aside>

        <div class="link-back">
            <a href="/posts">Go back</a>
        </div>
    </section>
    <footer>
        Copyright by <a href="https://github.com/olml89">Oleguer Mas</a>
    </footer>
</main>

</body>
</html>
