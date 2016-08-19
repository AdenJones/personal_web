<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Aden's Web Development</title>
        <meta charset="utf-8">
        <!-- Set window width to device -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS And JavaScript -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="{{URL::to('/')}}/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/styles/app.css">
        <link href="https://fonts.googleapis.com/css?family=Prosto+One" rel="stylesheet">
        <script src="{{URL::to('/')}}/js/jquery.bxslider.min.js"></script>
        <link href="{{URL::to('/')}}/styles/jquery.bxslider.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/styles/style.css">
        <script src="{{URL::to('/')}}/js/modernizr-custom.js"></script>
        <script>
        
        </script>
    </head>

    <body>
        <div class="container">
           <h1><a href="{{url('/')}}">Aden's Web Development</a></h1>
            
           <div class="nav_container">
               <nav class="navbar navbar-default">
                   <!-- Navbar Contents -->
               </nav>
           </div>
            
           @yield('slider')
           
           @yield('content')
       </div>
    </body>
</html>
