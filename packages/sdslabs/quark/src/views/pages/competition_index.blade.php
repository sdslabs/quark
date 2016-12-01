<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Competitions</title>

    </head>
    <body>
        <h3>Competitions</h3>

        @foreach($competitions as $competition)
        	<p>{{$competition->name}}</p>
       	@endforeach
    </body>
</html>