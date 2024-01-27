<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
</head>
<body>
    <h1>Scraped Data Article</h1>

    @if(!empty($articles))
        <!-- Display Search Results -->
        <ul>
            @foreach($articles as $article)
                <li>
                    <h2>{{ $article['title'] }}</h3>
                    <h3>{{ $article['key'] }}</h3>
                    <h3>{{ $article['resUrl'] }}</h3>
                </li>
            @endforeach
        </ul>
    @else
        <p>No results found</p>
    @endif

    <form method="GET" action="{{ route('searchRecipe') }}">
        <button type="submit">Back</button>
    </form>
</body>
</html>
