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
            $( document ).ready(function() {
              if (!Modernizr.backgroundcliptext) {
               
                 // not supported
                 $('div.container > h1 a').css('background','none');
               } 
            });
            
           
        </script>
    </head>

    <body>
        <div class="container">
           <h1><a href="{{url('/')}}">Aden's Web Development</a></h1>
            
           <div class="nav_container">
               <nav class="navbar navbar-default">
                   <!-- Navbar Contents -->
                   <div class="container-fluid">
                      <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                        </button>
                        
                      </div>
                      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                           <li><a class="navbar-brand" href="{{url('/')}}">Home</a></li>
                           <li><a class="navbar-brand" href="{{url('/contact')}}">Contact</a></li>
                        </ul>
                   </div>
               </nav>
           </div>
            
           @yield('slider')
           
           @yield('content')
       </div>
    </body>
</html>
