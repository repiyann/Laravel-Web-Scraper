<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
</head>
<body>
    <h1>Scraped Data</h1>

    <!-- Search Form -->
    <form method="GET" action="{{ route('scrape') }}">
        <label for="q">Search:</label>
        <input type="text" name="q" id="q" value="{{ $searchQuery }}" />
        <button type="submit">Search</button>
    </form>

    @if(!empty($recipes))
        <!-- Display Search Results -->
        <h2>Search Results for '{{ $searchQuery }}'</h2>
        <ul>
            @foreach($recipes as $recipe)
                <li>
                    <h2>{{ $recipe['title'] }}</h3>
                    <!-- Display other recipe information as needed -->
                    <img src="{{ $recipe['thumb'] }}" alt="{{ $recipe['title'] }} Thumbnail">
                    <h3>{{ $recipe['thumb'] }}</h3>
                    <h3>{{ $recipe['key'] }}</h3>
                    <h3>{{ $recipe['times'] }}</h3>
                    <h3>{{ $recipe['serving'] }}</h3>
                    <h3>{{ $recipe['difficulty'] }}</h3>
                </li>
            @endforeach
        </ul>
    @else
        <p>No results found for '{{ $searchQuery }}'</p>
    @endif
</body>
</html>
