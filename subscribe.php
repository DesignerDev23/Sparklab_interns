<?php
// Include your database connection
include './config/config.php';

// Start the session
session_start();

if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page
    header("Location: index.php");
    exit();
}

// Check if the user is logged in
$email = $_SESSION['email'];
$sql = "SELECT * FROM interns WHERE email = '$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['full_name'];
    $registrationId = $row['id'];
    $profilePhoto = $row['profile_picture'];
    $siwesDuration = $row['duration']; // Assuming this field exists
    $hubManagerApproval = $row['hub_manager_approval']; // Approval status
    $monthlyRate = 30000; // Monthly rate in Naira
    $totalAmount = $monthlyRate * $siwesDuration; // Calculate total amount based on duration

    // Store necessary session variables
    $_SESSION['intern_id'] = $registrationId;
    $_SESSION['siwes_duration'] = $siwesDuration;

    // Check payment history
    $paymentSql = "SELECT SUM(amount) as total_paid FROM intern_subscriptions WHERE intern_id = ?";
    $stmt = $conn->prepare($paymentSql);
    $stmt->bind_param("i", $registrationId);
    $stmt->execute();
    $paymentResult = $stmt->get_result();
    $paidAmount = 0;

    if ($paymentResult && $paymentResult->num_rows > 0) {
        $paymentRow = $paymentResult->fetch_assoc();
        $paidAmount = $paymentRow['total_paid'] ?? 0;

    }

    // Calculate outstanding balance
    $outstandingAmount = $totalAmount - $paidAmount;

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-wide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Payment | Spark Lab Hub</title>

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
    <script src="https://js.paystack.co/v1/inline.js"></script>


  </head>

  <body>
    <!-- Content -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
          <!-- Menu -->
  
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
       

              <li class="menu-item active">
                <a href="subscribe" class="menu-link ">
                  <i class="menu-icon  bx bx-credit-card"></i>
                  <div data-i18n="Dashboards">Subscribe</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              </li>

             

              <li class="menu-item ">
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
          <!-- / Menu -->
  
          <!-- Layout container -->
          <div class="layout-page">
            <!-- Navbar -->
  
            <nav
              class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
              id="layout-navbar">
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                  <i class="bx bx-menu bx-sm"></i>
                </a>
              </div>
  
              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
                <div class="navbar-nav align-items-center">
                  <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input
                      type="text"
                      class="form-control border-0 shadow-none ps-1 ps-sm-2"
                      placeholder="Search..."
                      aria-label="Search..." />
                  </div>
                </div>
                <!-- /Search -->
  
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                  <!-- Place this tag where you want the button to render. -->
              
  
                 <!-- User -->
                 <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                    <img src="./assets/img/avatars/boy.png" alt="Profile Photo" class="w-px-40 h-40 rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                            <img src="<?php echo $profilePhoto; ?>" alt="Profile Photo" class="w-px-40 h-40 rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                          <span class="fw-medium d-block"><?php echo $fullName; ?></span>
                          <small class="text-muted">SPK Intern</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle ms-1">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
                </ul>
              </div>
            </nav>
  
            <!-- / Navbar -->
  
            <!-- Content wrapper -->
            <div class="content-wrapper">
              <!-- Content -->
              <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="mb-4">Congratulations <?php echo $fullName; ?> 🎉</h5>
                            <p>Welcome back! Your SIWES duration is <?php echo $siwesDuration; ?> month(s).</p>
                            <p>Total Amount: NGN <?php echo number_format($totalAmount, 2); ?></p>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Approval Message -->
    <?php if ($hubManagerApproval !== 'approved') : ?>
        <div class="alert alert-warning">
            🚫 Your registration is pending approval. Please wait for the hub manager to approve your application.
        </div>
    <?php else : ?>
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Subscription</h5>
                    <small class="text-muted float-end">Make a new Subscription</small>
                </div>
                <div class="card-body">
                    <!-- Check if Payment is Completed -->
                    <?php if ($outstandingAmount <= 0) : ?>
                        <div class="alert alert-success">
                            ✅ Congratulations! You have completed your internship payment.
                        </div>
                    <?php else : ?>
                        <!-- Display Outstanding Balance -->
                        <div class="alert alert-info">
                            💰 You have paid NGN <?php echo number_format($paidAmount, 2); ?>.
                            Outstanding balance: NGN <?php echo number_format($outstandingAmount, 2); ?>.
                        </div>

                        <form id="internSubscriptionForm" method="POST">
                            <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="registrationId">Registration ID</label>
                                <div class="col-sm-10">
                                    <input type="text" id="registrationId" name="registrationId" value="<?php echo $registrationId; ?>"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="email">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Payment Type Selection -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="paymentType">Payment Type</label>
                                <div class="col-sm-10">
                                    <select id="paymentType" name="paymentType" class="form-control">
                                        <option value="one-time">One-Time Payment (₦<?php echo number_format($totalAmount); ?>)</option>
                                        <option value="split">Split Payment (₦<?php echo number_format($monthlyRate); ?> per month)</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="payAmount" name="payAmount" value="<?php echo $totalAmount; ?>">

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Payment</label>
                                <div class="col-sm-10">
                                    <button type="button" id="paystackBtn" class="btn btn-primary">Make Payment</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
                
                
              <!-- / Content -->
  
              <!-- Footer -->
              <footer class="footer bg-light">
                <div
                  class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-3">
                  <div>
                    <a
                      href="https://demos.themeselection.com/Spark Lab Hub-bootstrap-html-admin-template/html/vertical-menu-template/"
                      target="_blank"
                      class="footer-text fw-bold"
                      >Spark Lab Hub</a
                    >
                    ©
                  </div>
                  <div>
                    <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                    <a href="javascript:void(0)" class="footer-link me-4">Help</a>
                    <a href="javascript:void(0)" class="footer-link me-4">Contact</a>
                    <a href="javascript:void(0)" class="footer-link">Terms &amp; Conditions</a>
                  </div>
                </div>
              </footer>
              <!-- / Footer -->
  
              <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>
  
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      </div>

    <!-- / Content -->

  

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="assets/vendor/libs/bs-stepper/bs-stepper.js" /></script>


    <script>
document.getElementById('paymentType').addEventListener('change', function() {
    var totalAmount = document.querySelector('input[name="totalAmount"]').value;
    var monthlyRate = <?php echo $monthlyRate; ?>;
    var selectedPaymentType = this.value;
    
    // Set amount based on payment type
    var amountToPay = selectedPaymentType === 'one-time' ? totalAmount : monthlyRate;
    document.getElementById('payAmount').value = amountToPay;
});

document.getElementById('paystackBtn').addEventListener('click', function() {
    var email = document.getElementById('email').value;
    var amount = document.getElementById('payAmount').value;

    var handler = PaystackPop.setup({
        key: 'pk_test_12658c234f2075a824b3e5862ac5a6b31fc5cd4f', // Your Paystack public key
        email: email,
        amount: amount * 100, // Convert to kobo
        currency: 'NGN',
        ref: 'INTERN_' + Math.floor((Math.random() * 1000000000) + 1), 
        onClose: function() {
            alert('Payment closed');
        },
        callback: function(response) {
            var paymentReference = response.reference;
            submitForm(email, amount, paymentReference);
        }
    });
    handler.openIframe();
});

// Function to submit payment details
function submitForm(email, amount, paymentReference) {
    var formData = new FormData();
    formData.append('email', email);
    formData.append('amount', amount);
    formData.append('paymentReference', paymentReference);
    formData.append('paymentType', document.getElementById('paymentType').value); // Include payment type

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'process_subscription.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Subscription successful!');
            window.location.href = 'dashboard.php'; 
        } else {
            console.error('Error processing subscription: ' + xhr.statusText);
        }
    };
    xhr.send(formData);
}

    </script>


  </body>
</html>
