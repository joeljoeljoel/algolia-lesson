<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        
    </head>
    <body>
        <div class="container">
            <div class="content">
            <h1>Results</h1>
                <ul class="list-group">
                    @foreach ($results as $actor)
                    <li class="list-group-item">{{ $actor['name'] }}: {{ $actor['alternative_name'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
