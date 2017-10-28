<!DOCTYPE html>
<html>
  <head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset("css/materialize.min.css")}}"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>
  <body>
    <div class="navbar-fixed">
      <nav>
        <div class="nav-wrapper">
          <a href="#" class="brand-logo center">JuliKas</a>
          <ul id="nav-mobile" class="left hide-on-med-and-down">
            <li><a href="{{url("/")}}">Home</a></li>
            <li><a href="{{url("/products")}}">Products</a></li>
            <li><a href="{{url("/broadcast")}}">Broadcast</a></li>
          </ul>
        </div>
      </nav>
    </div>
    <div class="container">
      <div class="row">
        &nbsp;
      </div>
      @yield("content")
    </div>
  </body>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="{{asset("js/materialize.min.js")}}"></script>
  <script type="text/javascript" src="{{asset("js/app.js")}}"></script>
</html>