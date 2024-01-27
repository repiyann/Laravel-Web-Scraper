<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
</head>
<body>
    <h1>Scraped Data Category</h1>

    @if(!empty($categories))
        <!-- Display Search Results -->
        <ul>
            @foreach($categories as $category)
                <li>
                    <h2>{{ $category['title'] }}</h3>
                    <h3>{{ $category['key'] }}</h3>
                    <h3>{{ $category['resUrl'] }}</h3>
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
