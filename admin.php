<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f6ff; 
            margin: 0;
            padding: 0;
            
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
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
<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();  // Start the session at the very top of the script

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials for login
    if ($username === "dbmsproject123" && $password === "dbmsproject123") {
        $login = true;
        
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        // Redirect to adminf.php
        header("Location: adminf.php");
        exit;
    } else {
        $showError = "Invalid credentials";
    }
}
?>
<?php require 'partials/_nav.php'; ?>
<?php
        if ($login) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> You are logged in.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        if ($showError) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Sorry!</strong> '.$showError.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    ?>

    <div class="container">
        <h2>Admin Login Here</h2>
        <form action="/shreya/admin.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>