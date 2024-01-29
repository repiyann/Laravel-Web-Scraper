<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $recipeData['title'] }}</title>
</head>
<body>

<div>
    <h1>{{ $recipeData['title'] }}</h1>
    <img src="{{ $recipeData['thumbnail'] }}" alt="Recipe Thumbnail">

    <div>
        <p><strong>Cook Time:</strong> {{ $recipeData['cookTime'] }}</p>
        <p><strong>Difficulty:</strong> {{ $recipeData['difficulty'] }}</p>
        <p><strong>Writer:</strong> {{ $recipeData['writer'] }}</p>
        <p><strong>Description:</strong> {{ $recipeData['description'] }}</p>
        <p><strong>Portions:</strong> {{ $recipeData['portions'] }}</p>
    </div>

    <h2>Ingredients:</h2>
    <ul>
        @foreach($recipeData['ingredients'] as $ingredient)
            <li>{{ $ingredient }}</li>
        @endforeach
    </ul>

    <h2>Steps:</h2>
    <ul>
        @foreach($recipeData['steps'] as $step)
            <li>{{ $step }}</li>
        @endforeach
    </ul>
</div>

</body>
</html>
