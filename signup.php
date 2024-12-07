<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['conpas'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "vsms");
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM signup WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $numExistRows = $result->num_rows;

    if ($numExistRows > 0) {
        $showError = "Username already exists";
    } else {
        // Password match check
        if ($password == $confirm_password) {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO signup (username, password, dt) VALUES (?, ?, current_timestamp())");
            $stmt->bind_param("ss", $username, $hashed_password);
            $result = $stmt->execute();

            if ($result) {
                $showAlert = true;
            }
        } else {
            $showError = "Passwords do not match";
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sign up</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5; 
      }

      .container {
        max-width: 600px;
        margin: 50px;
        padding: 20px;
        background-color: #f0f8ff; 
        border-radius: 10px;
        box-shadow: 0px 0px 10px 0px rgba(5, 0, 0); 
      }
.container:hover{
  background-color:#ccccff;
}
      h2 {
        text-align: center;
        color: #0056b3; 
      }

      .form-label {
        font-weight: bold;
      }

      .form-control {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
      }

      .btn-primary {
        background-color: #0056b3; 
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
      }

      .btn-primary:hover {
        background-color: #003d80; 
      }

      .alert {
        margin-bottom: 20px; 
        padding: 15px;
        border-radius: 5px;
      }

      .alert-warning {
        background-color: #e2efff; 
        border: 1px solid #b8d9ff; 
        color: #0056b3; 
      }

      .alert-danger {
        background-color: #ffe8e8; 
        border: 1px solid #ff8080; 
        color: #cc0000; 
      }
    </style>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
    <?php
      if($showAlert){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Success!</strong> your account has been created.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      }
      if($showError){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Sorry!</strong> '.$showError.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      }
    ?>
    <div class="container">
      <h2>Sign Up Here</h2>
      <form action="/shreya/signup.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control"  name="username" id="username" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
           <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control"   required pattern="[0-9a-z]{2,}"  placeholder="should contain min 4 numbers may include alphabets" id="password" name="password">
        </div> 
        <div class="mb-3">
          <label for="conpas" class="form-label">Confirm Password</label>
          <input type="password"  required pattern="[0-9a-z]{2,}" class="form-control" id="conpas" name="conpas">
          <div id="emailHelp" class="form-text">Make sure you enter the same as above.</div>
        </div>
        <button type="submit" class="btn btn-primary">Sign up</button>
      </form>
    </div>
  </body>
</html>
