<!-- navbar -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Swift N Fix</title>
  <style>
    .logo{
      position:fixed;
      height:auto;
      left:0px;
      width:250px;
    }
   </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <a class="nav-link" aria-current="page" href="#"><img src="logo swift.jpg" width="40px"></a>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/shreya/login.php">Home</a>
          </li>
          <?php
          //  if(isset($_SESSION['username']) && $_SESSION['username'] === 'dbmsproject123' && isset($_SESSION['password'])&& $_SESSION['password'] === 'dbmsproject123') {
          //    echo '
          //    <li class="nav-item">
          //     <a class="nav-link" href="/shreya/loggingout.php"> admin Logout</a>
          //    </li>';
          //  }
            if(isset($_SESSION['loggedin'])){
              echo '
              <li class="nav-item">
              <a class="nav-link" href="/shreya/logout.php">Logout</a>
              </li>';
            }
              else {
              echo '
              <li class="nav-item">
                <a class="nav-link" href="/shreya/signup.php">Sign up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/shreya/log.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/shreya/location.php">Location</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/shreya/admin.php">Admin Login</a>
              </li>';
            }
           ?>
        </ul>
      </div>
    </div>
  </div>
</nav>
</body>
</html>
