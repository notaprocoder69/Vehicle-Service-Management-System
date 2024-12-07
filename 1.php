<?php
session_start();
if(!isset($_SESSION['loggedin'])||$_SESSION['loggedin']!=true){
  header("location:log.php");
  exit;
}
?>
<?php
$showalert=false;
$showerror=false;
if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    $review = $_POST['review'];
    $customer_id = $_POST['customer_id'];
    $date=date('Y-m-d H:i:s');
    $conn = mysqli_connect("localhost","root","", "vsms");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "INSERT INTO rating_review (`customer_id`,`review`,`date`) VALUES ('$customer_id','$review','$date')";
    $res=mysqli_query($conn, $sql);
    if ($res) {
        $showalert=true;
        } else {
        $showerror=true;
        //echo "Error:"; 
        //. $sql . "<br>" . mysqli_error($conn);
    } 
    mysqli_close($conn);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color:#0d7983; 
        }

        .container {
            background-color: #f0f8ff; 
            padding: 30px;
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
<?php
if($showalert){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!  </strong>Now you can logout.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if($showerror){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Error!  </strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>
<div class="container mt-5">
    <h2>Rate your experience</h2>
    <form action="/shreya/1.php" method="post">
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer id</label>
            <input type="text" class="form-control" id="customer_id" name="customer_id" class="customer_id" placeholder="Enter number given same as in appointment">
        </div> 
        <div class="mb-3">
            <label for="review" class="form-label">Review</label>
            <input type="text" class="form-control" id="review" rows="3" name="review" placeholder="Give your review"></textarea>
        </div>
     <button type="submit" class="btn btn-primary">Submit</button>
        </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
