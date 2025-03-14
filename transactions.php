<?php
// Include your database configuration
include './config/config.php';

// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];

// Retrieve intern data
$sql = "SELECT * FROM interns WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$subscriptions = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['full_name'];
    $profilePhoto = $row['profile_picture'];
    $internId = $row['id'];

    // Fetch intern subscription details
    $subscriptionSql = "SELECT * FROM intern_subscriptions WHERE intern_id = ? ORDER BY created_at DESC";
    $subStmt = $conn->prepare($subscriptionSql);
    $subStmt->bind_param("i", $internId);
    $subStmt->execute();
    $subscriptionResult = $subStmt->get_result();

    while ($subscriptionRow = $subscriptionResult->fetch_assoc()) {
        $subscriptions[] = [
            'id' => $subscriptionRow['id'],
            'amount' => number_format($subscriptionRow['amount'], 2),
            'payment_reference' => $subscriptionRow['payment_reference'],
            'status' => ucfirst($subscriptionRow['status']),
            'created_at' => date("d M Y, H:i A", strtotime($subscriptionRow['created_at'])),
        ];
    }

    $subStmt->close();
}

$stmt->close();
$conn->close();
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

    <title>Transaction | Spark Lab Hub</title>

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
     <!-- Add Bootstrap CSS -->
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.1/css/bootstrap.min.css">

    <!-- Add DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <!-- Add DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">

    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Add DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <!-- Add DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>



  </head>

  <style>
/* Modal Styling */
.modal { display: none; position: fixed; z-index: 1000; padding-top: 100px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
.modal-content { background-color: #fff; margin: auto; padding: 20px; border: 1px solid #888; width: 60%; }
.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: black; }
</style>


  <body>
    <!-- Content -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
          <!-- Menu -->
  
          <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
              <a href="dashboard" class="app-brand-link">
                <span class="app-brand-text demo menu-text fw-bold  ms-2 text-capitalize">Spark Lab Hub</span>

              </a>
  
              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>
  
            <div class="menu-inner-shadow"></div>
  
            <ul class="menu-inner py-1">
              <!-- Dashboards -->
              <li class="menu-item ">
                <a href="dashboard" class="menu-link ">
                  <i class="menu-icon  bx bx-home-circle"></i>
                  <div data-i18n="Dashboards">Dashboards</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              
              </li>

              <li class="menu-header small text-uppercase"><span class="menu-header-text">Subscription</span></li>
              <!-- Layouts -->
              <!-- <li class="menu-item ">
                <a href="add_subscriber.php" class="menu-link">
                  <i class="menu-icon  bx bx-user-plus"></i>
                  <div data-i18n="Dashboards">Add Subscriber</div>
                </a>
              </li> -->

              <li class="menu-item ">
                <a href="subscribe" class="menu-link ">
                  <i class="menu-icon  bx bx-credit-card"></i>
                  <div data-i18n="Dashboards">Subscribe</div>
                  <!-- <div class="badge bg-danger rounded-pill ms-auto">5</div> -->
                </a>
              </li>

              <!-- <li class="menu-item ">
                <a href="manage_subscribers.php" class="menu-link ">
                  <i class="menu-icon  bx bx-group"></i>
                  <div data-i18n="Dashboards">Manage Subscribers</div>
             
                </a>
              </li> -->

  
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


              <li class="menu-item active">
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
                  <!-- <li class="nav-item lh-1 me-3">
                    <a
                      class="github-button"
                      href="https://github.com/themeselection/Spark Lab Hub-html-admin-template-free"
                      data-icon="octicon-star"
                      data-size="large"
                      data-show-count="true"
                      aria-label="Star themeselection/Spark Lab Hub-html-admin-template-free on GitHub"
                      >Star</a
                    >
                  </li> -->
  
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
                            <img src="./assets/img/avatars/boy.png" alt="Profile Photo" class="w-px-40 h-40 rounded-circle" />
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
                                      <h5 class="card-title text-primary">Congratulations <?php echo htmlspecialchars($fullName); ?> 🎉</h5>
                                      <p class="mb-4">
                                          Welcome back! Keep track of your payments below.
                                      </p>
                                  </div>
                              </div>
                              <div class="col-sm-5 text-center text-sm-left">
                                  <div class="card-body pb-0 px-0 px-md-4">
                                      <img src="assets/img/illustrations/man-with-laptop-light.png" height="140" alt="User Image"/>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Subscription Table -->
                        <div class="col-xxl">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Payment History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Amount (NGN)</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($subscriptions as $subscription): ?>
                                                    <tr>
                                                        <td><?php echo $subscription['id']; ?></td>
                                                        <td><?php echo $subscription['amount']; ?></td>
                                                        <td><?php echo htmlspecialchars($subscription['payment_reference']); ?></td>
                                                        <td><span class="badge bg-<?php echo ($subscription['status'] === 'Paid') ? 'success' : 'warning'; ?>"><?php echo $subscription['status']; ?></span></td>
                                                        <td><?php echo $subscription['created_at']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary view-transaction" data-id="<?php echo $subscription['id']; ?>">View</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Modal for Transaction Details -->
                <div id="transactionModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <div id="transactionDetails"></div>
                    </div>
                </div>
              </div>
            </div>
            </div>
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
    	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            paging: true,
            responsive: true
        });

        // Handle transaction view button click
        $('.view-transaction').on('click', function() {
            var transactionId = $(this).data('id');
            viewTransaction(transactionId);
        });
    });


    function viewTransaction(transactionId) {
        var transactions = <?php echo json_encode($subscriptions); ?>;
        var transaction = transactions.find(t => t.id == transactionId);

        if (transaction) {
            var modalContent = "<h2>Transaction Details</h2>";
            modalContent += "<p><strong>ID:</strong> " + transaction.id + "</p>";
            modalContent += "<p><strong>Amount:</strong> " + transaction.amount + " NGN</p>";
            modalContent += "<p><strong>Reference:</strong> " + transaction.payment_reference + "</p>";
            modalContent += "<p><strong>Status:</strong> " + transaction.status + "</p>";
            modalContent += "<p><strong>Date:</strong> " + transaction.created_at + "</p>";

            document.getElementById("transactionDetails").innerHTML = modalContent;
            document.getElementById("transactionModal").style.display = "block";
        }
    }

    function closeModal() {
        document.getElementById("transactionModal").style.display = "none";
    }
  </script>




  </body>
</html>
