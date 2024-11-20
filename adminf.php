<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vsms";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname, 3308);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total revenue
function getTotalRevenue($conn) {
    $sql = "SELECT COALESCE(SUM(CAST(cost AS DECIMAL(10,2))), 0.00) AS total_revenue FROM payment";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Error in getTotalRevenue: " . $conn->error);
        return 0.00;
    }
    
    $row = $result->fetch_assoc();
    return $row ? (float)$row['total_revenue'] : 0.00;
}

$totalRevenue = getTotalRevenue($conn);

// Function to get service history for a vehicle
function getServiceHistory($conn, $vehicle_id) {
    $sql = "SELECT s.date AS service_date, s.description AS service_description, 
            s.cost AS service_cost, t.name AS technician_name
            FROM service s
            LEFT JOIN technician t ON s.tech_id = t.tech_id
            WHERE s.vehicle_id = ?
            ORDER BY s.date DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Updated query to get top spending customer
function getTopSpendingCustomer($conn) {
    $sql = "
        SELECT 
            c.cust_id,
            c.name,
            c.phone_number,
            c.email,
            COALESCE(SUM(CAST(p.cost AS DECIMAL(10,2))), 0.00) as total_spent
        FROM 
            customer c
        LEFT JOIN 
            payment p ON c.cust_id = p.cust_id
        GROUP BY 
            c.cust_id, c.name, c.phone_number, c.email
        HAVING 
            total_spent > 0
        ORDER BY 
            total_spent DESC
        LIMIT 1
    ";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Error in getTopSpendingCustomer: " . $conn->error);
        return null;
    }
    
    return $result->fetch_assoc();
}

// Make sure to calculate both totalRevenue and topSpendingCustomer before using them
$totalRevenue = getTotalRevenue($conn);
$topSpendingCustomer = getTopSpendingCustomer($conn);

// Updated main query to match schema relationships
$sql = "
    SELECT 
        c.name AS customer_name,
        c.phone_number AS customer_phone,
        c.email AS customer_email,
        c.address AS customer_address,
        v.model AS vehicle_model,
        a.date AS appointment_date,
        a.service_type AS service_type,
        t.name AS technician_name,
        s.description AS service_description,
        s.cost AS service_cost,
        p.cost AS payment_amount,
        r.review AS customer_review,
        r.date AS review_date
    FROM 
        customer c
    LEFT JOIN vehicle v ON c.cust_id = v.cust_id
    LEFT JOIN appointment a ON v.vehicle_id = a.vehicle_id
    LEFT JOIN service s ON v.vehicle_id = s.vehicle_id
    LEFT JOIN technician t ON s.tech_id = t.tech_id
    LEFT JOIN payment p ON s.service_id = p.service_id
    LEFT JOIN rating_review r ON c.cust_id = r.customer_id
    ORDER BY 
        a.date DESC
";

// Execute the main query
$result = $conn->query($sql);
if (!$result) {
    echo "Error executing query: " . $conn->error;
}
// Updated aggregate query and execution
$aggregateSql = "
    SELECT
        COALESCE(AVG(CAST(cost AS DECIMAL(10,2))), 0.00) AS average_service_cost,
        COUNT(DISTINCT service_id) AS total_services
    FROM service";

$aggregateResult = $conn->query($aggregateSql);

if ($aggregateResult) {
    $aggregateData = $aggregateResult->fetch_assoc();
    $averageServiceCost = $aggregateData['average_service_cost'];
    $totalServices = $aggregateData['total_services'];
} else {
    $averageServiceCost = 0.00;
    $totalServices = 0;
}


// Fetch dropdown options for vehicle_id
$sql_vehicle = "SELECT DISTINCT vehicle_id FROM vehicle";
$result_vehicle = $conn->query($sql_vehicle);
$vehicle_idOptions = "";
if ($result_vehicle->num_rows > 0) {
    while ($row_vehicle = $result_vehicle->fetch_assoc()) {
        $vehicle_idOptions .= "<option value='".$row_vehicle['vehicle_id']."'>".$row_vehicle['vehicle_id']."</option>";
    }
}

// Fetch dropdown options for service_type
$sql_service = "SELECT DISTINCT service_type FROM appointment";
$result_service = $conn->query($sql_service);
$serviceOptions = "";
if ($result_service->num_rows > 0) {
    while ($row_service = $result_service->fetch_assoc()) {
        $serviceOptions .= "<option value='".$row_service['service_type']."'>".$row_service['service_type']."</option>";
    }
}

// Fetch dropdown options for tech_id
$sql_technician = "SELECT tech_id, name FROM technician";
$result_technician = $conn->query($sql_technician);
$techOptions = "";
if ($result_technician->num_rows > 0) {
    while ($row_technician = $result_technician->fetch_assoc()) {
        $techOptions .= "<option value='".$row_technician['tech_id']."'>".$row_technician['name']."</option>";
    }
}

// Fetch dropdown options for cust_id
$sql_customer = "SELECT cust_id, name FROM customer";
$result_customer = $conn->query($sql_customer);
$cust_idOptions = "";
if ($result_customer->num_rows > 0) {
    while ($row_customer = $result_customer->fetch_assoc()) {
        $cust_idOptions .= "<option value='".$row_customer['cust_id']."'>".$row_customer['name']."</option>";
    }
}

$showalert = false;
$errorMessage = "";

// Handle form submission with transaction
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();
    try {
        $vehicle_id = $_POST['vehicle_id'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];
        $tech_id = $_POST['tech_id'];
        $cust_id = $_POST['cust_id'];
        $date = date('Y-m-d');

        // Generate a unique service_id
        $stmt = $conn->prepare("SELECT MAX(service_id) as max_id FROM service");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $service_id = ($row['max_id'] ?? 0) + 1;
        $stmt->close();

        // Generate a unique payment_id
        $stmt = $conn->prepare("SELECT MAX(payment_id) as max_id FROM payment");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $payment_id = ($row['max_id'] ?? 0) + 1;
        $stmt->close();

        // Insert into service table
        $stmt = $conn->prepare("INSERT INTO service (service_id, vehicle_id, description, cost, date, tech_id) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing service statement: " . $conn->error);
        }
        $stmt->bind_param("iisdsi", $service_id, $vehicle_id, $description, $cost, $date, $tech_id);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting service: " . $stmt->error);
        }
        $stmt->close();

        // Insert into payment table with payment_id
        $stmt_payment = $conn->prepare("INSERT INTO payment (payment_id, service_id, cost, cust_id) VALUES (?, ?, ?, ?)");
        if (!$stmt_payment) {
            throw new Exception("Error preparing payment statement: " . $conn->error);
        }
        $stmt_payment->bind_param("iiii", $payment_id, $service_id, $cost, $cust_id);
        if (!$stmt_payment->execute()) {
            throw new Exception("Error inserting payment: " . $stmt_payment->error);
        }
        $stmt_payment->close();

        $conn->commit();
        $showalert = true;
    } catch (Exception $e) {
        $conn->rollback();
        $errorMessage = $e->getMessage();
    }
}

// Rest of your HTML and display code remains the same...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body { background-color: #230D83; }
      .container { background-color: #f0f8ff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(5, 0, 0); }
      .container:hover, .btn-primary:hover { background-color:#CCCCFF; }
      .form-control { border-color: #007bff; }
      .form-control:focus { border-color: #0056b3; box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); }
    </style>
</head>
<body>
<?php require 'partials/_nav.php' ?>
<?php
if ($showalert) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Application saved.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}

if ($errorMessage) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errorMessage . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>
<div class="container mt-5">
    <h2>Enter Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label for="vehicle_id" class="form-label">Vehicle ID</label>
            <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                <?php echo $vehicle_idOptions; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <select name="description" id="description" class="form-select" required>
                <?php echo $serviceOptions; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cost" class="form-label">Amount</label>
            <input type="number" class="form-control" id="cost" name="cost" placeholder="Enter Amount" required>
        </div>
        <div class="mb-3">
            <label for="tech_id" class="form-label">Technician ID</label>
            <select name="tech_id" id="tech_id" class="form-select" required>
                <?php echo $techOptions; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cust_id" class="form-label">Customer ID</label>
            <select name="cust_id" id="cust_id" class="form-select" required>
                <?php echo $cust_idOptions; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <br>
    <br>
    <br>
    <br>

    <!-- Display total revenue -->
    <h3 class="mt-5">Total Revenue Earned: $<?php echo number_format((float)$totalRevenue, 2, '.', ','); ?></h3>

    <hr class="my-4">
    <br>
    <br>
    <br>
    <br>

    <!-- Vehicle Service History Section -->
    <h2 class="mt-5">View Vehicle Service History</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="mb-3">
            <label for="history_vehicle_id" class="form-label">Select Vehicle ID</label>
            <select name="history_vehicle_id" id="history_vehicle_id" class="form-select" required>
                <?php echo $vehicle_idOptions; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">View Service History</button>
    </form>

    <?php
    // Display service history if vehicle ID is selected
    if (isset($_GET['history_vehicle_id'])) {
        $history_vehicle_id = $_GET['history_vehicle_id'];
        $serviceHistory = getServiceHistory($conn, $history_vehicle_id);

        if ($serviceHistory->num_rows > 0) {
            echo "<h4 class='mt-4'>Service History for Vehicle ID: " . htmlspecialchars($history_vehicle_id) . "</h4>";
            echo "<table class='table table-striped mt-3'>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Technician</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $serviceHistory->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['service_date']) . "</td>
                        <td>" . htmlspecialchars($row['service_description']) . "</td>
                        <td>$" . htmlspecialchars(number_format($row['service_cost'], 2)) . "</td>
                        <td>" . htmlspecialchars($row['technician_name']) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='mt-4'>No service history found for this vehicle.</p>";
        }
    }
    ?>
    <br>
    <br>
    <br>
    <br>

    <!-- Display the top-spending customer -->
    <h2 class="mt-5">Top Spending Customer</h2>
    <?php
    if ($topSpendingCustomer && $topSpendingCustomer['total_spent'] > 0) {
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h4 class='card-title'>Customer Details:</h4>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($topSpendingCustomer['name']) . "</p>";
        echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($topSpendingCustomer['phone_number']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($topSpendingCustomer['email']) . "</p>";
        echo "<p><strong>Total Amount Spent:</strong> $" . number_format((float)$topSpendingCustomer['total_spent'], 2, '.', ',') . "</p>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-info'>No customer spending data available yet.</div>";
    }
    ?>

<div class="container mt-5">
    <h2>Service and Payment History</h2>

    <?php
    // Check if there are any records
    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Vehicle Model</th>
                        <th>Appointment Date</th>
                        <th>Service Type</th>
                        <th>Technician</th>
                        <th>Service Description</th>
                        <th>Service Cost</th>
                        <th>Payment Amount</th>
                        <th>Review</th>
                        <th>Review Date</th>
                    </tr>
                </thead>
                <tbody>";

        // Fetch and display the results
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['customer_name']) . "</td>
                    <td>" . htmlspecialchars($row['customer_phone']) . "</td>
                    <td>" . htmlspecialchars($row['customer_email']) . "</td>
                    <td>" . htmlspecialchars($row['customer_address']) . "</td>
                    <td>" . htmlspecialchars($row['vehicle_model']) . "</td>
                    <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                    <td>" . htmlspecialchars($row['service_type']) . "</td>
                    <td>" . htmlspecialchars($row['technician_name']) . "</td>
                    <td>" . htmlspecialchars($row['service_description']) . "</td>
                    <td>$" . number_format($row['service_cost'], 2) . "</td>
                    <td>$" . number_format($row['payment_amount'], 2) . "</td>
                    <td>" . (isset($row['customer_review']) ? htmlspecialchars($row['customer_review']) : "No Review") . "</td>
                    <td>" . (isset($row['review_date']) ? htmlspecialchars($row['review_date']) : "N/A") . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No records found.</div>";
    }

    
    ?>

</div>
<div class="container mt-5">
    <h2>Service and Payment History</h2>

    <!-- Aggregate Data Section -->
    <h4 class="mt-5">Business Metrics</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Service Cost</h5>
                    <p class="card-text">$<?php echo number_format((float)$averageServiceCost, 2, '.', ','); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Services</h5>
                    <p class="card-text"><?php echo $totalServices; ?> Services</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php $conn->close(); ?>
</body>
</html>
