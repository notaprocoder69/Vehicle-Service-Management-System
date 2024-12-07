<?php
session_start();

// Display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: log.php");
    exit;
}

$showAlert = false;
$showError = false;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "vsms";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $cust_id = mysqli_real_escape_string($conn, $_POST['cust_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone= mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);

    // Check if customer already exists
    $checkCustomer = "SELECT * FROM `customer` WHERE `cust_id` = '$cust_id'";
    $resultCustomer = mysqli_query($conn, $checkCustomer);

    if (mysqli_num_rows($resultCustomer) == 0) {
        // Insert customer data if it doesn't exist
        $sql = "INSERT INTO `customer` (`cust_id`, `name`, `phone_number`, `email`, `address`) 
                VALUES ('$cust_id', '$name', '$phone', '$email', '$address')";
        if (mysqli_query($conn, $sql)) {
            $showAlert = true;
        } else {
            $showError = "Error: " . mysqli_error($conn);
        }
    }

    // Check if vehicle already exists
    $checkVehicle = "SELECT * FROM `vehicle` WHERE `vehicle_id` = '$vehicle_id'";
    $resultVehicle = mysqli_query($conn, $checkVehicle);

    if (mysqli_num_rows($resultVehicle) == 0) {
        // Insert vehicle data if it doesn't exist
        $sql1 = "INSERT INTO `vehicle` (`vehicle_id`, `model`, `cust_id`) 
                 VALUES ('$vehicle_id', '$model', '$cust_id')";
        if (mysqli_query($conn, $sql1)) {
            $showAlert = true;
        } else {
            $showError = "Error: " . mysqli_error($conn);
        }
    }

    // Insert appointment data
    $date = date('Y-m-d H:i:s');
    $apt_id = date('YmdHis') . mt_rand(1000, 9999);
    $sql2 = "INSERT INTO `appointment` (`apt_id`, `cust_id`, `vehicle_id`, `date`, `service_type`) 
             VALUES ('$apt_id', '$cust_id', '$vehicle_id', '$date', '$service_type')";
    if (mysqli_query($conn, $sql2)) {
        $showAlert = true;
    } else {
        $showError = "Error: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);

    // Redirect to 1.php if everything was successful
    if ($showAlert) {
        header("Location: 1.php");
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APPOINTMENTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      body {
        background-color: #230D83; 
      }
      
      .container {
        background-color: #f0f8ff; 
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(5, 0, 0);
      }

      .container:hover,
      .btn-primary:hover {
        background-color:#CCCCFF; 
      }

      .form-control {
        border-color: #007bff; 
      }

      .form-control:focus {
        border-color: #0056b3; 
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); 
      }
    </style>
  </head>
  <body>
  <?php require 'partials/_nav.php' ?>
  <!-- <?php
    if($showalert){
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Success!  </strong>Application saved.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    if($showError){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Sorry!</strong> '.$showError.'
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
  ?> -->
    <div class="container mt-5">
      <h2> <?php echo $_SESSION['username'] ?>...!Book your appointment now</h2>
      <form action="/shreya/booking.php" method="post">
        <div class="mb-3">
          <label for="cust_id" class="form-label">Customer id</label>
          <input type="text" class="form-control" id="cust_id" name="cust_id"  placeholder="Enter last 4 digits of your vehicle">
        </div> 
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">Phone no.</label>
          <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter your phone no.">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email"  name="email" placeholder="Enter your email">
        </div>
        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" name="address"  placeholder="Enter your address">
        </div>
        <div class="mb-3">
          <label for="vehicle_id" class="form-label">Vehicle id</label>
          <input type="text" class="form-control" id="vehicle_id" name="vehicle_id"  placeholder="Enter your vehicle no.">
        </div>
        <div class="mb-3">
          <label for="model" class="form-label">Model</label>
          <select class="form-select" name="model">
  <option selected>Type of vehicle</option>
  <option value="car">Car</option>
  <option value="bike">Bike</option>
  <option value="truck">Truck</option>
  <option value="auto">Auto</option>
  
</select>
        </div>
        <div class="mb-3">
           <label for="service_type" class="form-label">Service type</label>
  <select class="form-select" id="service_type" name="service_type">
  <option selected>Type of service required</option>
  <option value="Regular Maintenance Checks">1.Regular Maintenance Checks</option> 
  <option value="Engine Diagnostics">2.Engine Diagnostics</option>
  <option value="Brake and Suspension Repairs">3.Brake and Suspension Repairs</option>
  <option value="Oil Changes">4.Oil Changes</option>
  <option value="Tire Services">5.Tire Services</option>
  <option value="Electrical System Repairs">6.Electrical System Repairs</option>
  <option value="Bodywork and Paint Services">7.Bodywork and Paint Services</option>
  <option value="Air Conditioning Maintenance">8.Air Conditioning Maintenance</option>

</select>
    </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
