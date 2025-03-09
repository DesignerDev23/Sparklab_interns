<?php
// Include your database connection
include './config/config.php';

// Start the session
session_start();

if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page
    header("Location: index.php");
    exit(); // Stop further execution
}

// Check if the user is logged in and retrieve intern data
$email = $_SESSION['email'];
$sql = "SELECT * FROM interns WHERE email = '$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['full_name'];
    $registrationID = $row['id'];
    $profilePhoto = $row['profile_picture'];
} else {
    // Handle no intern found
    echo "No intern found";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $registrationId = $_POST['registrationId'];

    // Check for an active subscription using the registration ID
    $subscriptionQuery = "SELECT * FROM intern_subscriptions WHERE intern_id = '$registrationId' AND expiration_date > NOW()";
    $subscriptionResult = $conn->query($subscriptionQuery);

    if ($subscriptionResult && $subscriptionResult->num_rows > 0) {
        // Intern has an active subscription, proceed with storing check-in information

        // Prepare data for check-in table insertion
        $checkInDate = date('Y-m-d H:i:s'); // Current date and time
        $status = 'present'; // Default status for the check-in
        
        // Insert check-in information into the check-in table
        $checkInSql = "INSERT INTO interns_check_in (intern_id, name, email, check_in_date, status)
                       VALUES ('$registrationId', '$fullName', '$email', '$checkInDate', '$status')";

        if ($conn->query($checkInSql) === TRUE) {
            echo "<script> alert('Check-in information stored successfully!'); </script>";
        } else {
            echo "Error: " . $checkInSql . "<br>" . $conn->error;
        }
    } else {
        echo "<script> alert('Intern does not have an active subscription.'); </script>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Check-in | Spark Lab Hub</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
              <a href="dashboard" class="app-brand-link">
                <span class="app-brand-logo demo">

                  </span>
                <span class="app-brand-text demo menu-text fw-bold  ms-2 text-capitalize">Spark Lab Hub</span>

              </a>
  
              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>
  
            <div class="menu-inner-shadow"></div>
  
             <ul class="menu-inner py-1">
              <!-- Dashboards -->
              <li class="menu-item">
                <a href="dashboard" class="menu-link ">
                  <i class="menu-icon  bx bx-home-circle"></i>
                  <div data-i18n="Dashboards">Dashboards</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
          
              </li>

              <li class="menu-header small text-uppercase"><span class="menu-header-text">Subscription</span></li>
       

              <li class="menu-item ">
                <a href="subscribe" class="menu-link ">
                  <i class="menu-icon  bx bx-credit-card"></i>
                  <div data-i18n="Dashboards">Subscribe</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              </li>

             

              <li class="menu-item active">
                <a href="check_in" class="menu-link ">
                  <i class="menu-icon  bx bx-log-in-circle"></i>
                  <div data-i18n="Dashboards">Check-In</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              </li>

              <!-- <li class="menu-item ">
                <a href="active_user.php" class="menu-link ">
                  <i class="menu-icon  bx bx-user-check"></i>
                  <div data-i18n="Dashboards">Active Subscribers</div>
                </a>
              </li> -->
              
              <li class="menu-header small text-uppercase"><span class="menu-header-text">Activities</span></li>
              <!-- Layouts -->


              <li class="menu-item ">
                <a href="transactions" class="menu-link ">
                  <i class="menu-icon  bx bx-credit-card"></i>
                  <div data-i18n="Dashboards">Transactions</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              </li>
             
             
  
            
       </aside>

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <!-- Navbar Content -->
                </nav>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="col-xxl">
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Check-In</h5>
                                    <small class="text-muted float-end">Let's Know your Status</small>
                                </div>
                                <div class="card-body">
                                    <form id="internCheckinForm" method="POST" action="check_in.php">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="fullName">Full Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="fullName" value="<?php echo $fullName; ?>" name="fullName" required readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="registrationId">Registration ID</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="registrationId" value="<?php echo $registrationID; ?>" name="registrationId" required readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" class="btn btn-primary">Check In</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="footer bg-light">
                        <div class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-3">
                            <div>
                                <a href="#" target="_blank" class="footer-text fw-bold">Spark Lab Hub</a> ©
                            </div>
                            <div>
                                <a href="#" class="footer-link me-4" target="_blank">License</a>
                                <a href="#" class="footer-link me-4">Help</a>
                                <a href="#" class="footer-link me-4">Contact</a>
                                <a href="#" class="footer-link">Terms & Conditions</a>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>