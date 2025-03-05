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

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Retrieve user data from the database
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM subscribers WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullName = $row['full_name'];
        $registrationId = $row['registration_id'];
        $profilePhoto = $row['profile_photo'];

        // Close the database connection
        $conn->close();
    }
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

    <title>Profile | Spark Lab Hub</title>

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
    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="dashboard" class="app-brand-link">
              <span class="app-brand-logo demo"></span>
              <span class="app-brand-text demo menu-text fw-bold ms-2 text-capitalize">Spark Lab Hub</span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>
          <div class="menu-inner-shadow"></div>
          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item">
              <a href="dashboard" class="menu-link">
                <i class="menu-icon bx bx-home-circle"></i>
                <div data-i18n="Dashboards">Dashboards</div>
              </a>
            </li>
            <!-- Profile -->
            <li class="menu-item active">
              <a href="profile" class="menu-link">
                <i class="menu-icon bx bx-user"></i>
                <div data-i18n="Profile">Profile</div>
              </a>
            </li>
            <!-- Logout -->
            <li class="menu-item">
              <a href="logout.php" class="menu-link">
                <i class="menu-icon bx bx-power-off"></i>
                <div data-i18n="Logout">Log Out</div>
              </a>
            </li>
          </ul>
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
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="<?php echo $profilePhoto; ?>" alt="Profile Photo" class="w-px-40 h-40 rounded-circle" />
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
                    <a class="dropdown-item" href="profile">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
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
                          <h5 class="card-title text-primary">Profile Information</h5>
                          <p class="mb-4">
                            Manage your profile details and update your information.
                          </p>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="<?php echo $profilePhoto; ?>"
                            alt="Profile Photo"
                            class="rounded-circle"
                            width="150"
                            height="150" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Profile Details -->
                <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Profile Details</h5>
                    </div>
                    <div class="card-body">
                      <form id="profileForm" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="fullName">Full Name</label>
                          <div class="col-sm-10">
                            <input
                              type="text"
                              id="fullName"
                              name="fullName"
                              value="<?php echo $fullName; ?>"
                              class="form-control"
                              placeholder="John Doe"
                              required />
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="email">Email</label>
                          <div class="col-sm-10">
                            <input
                              type="email"
                              id="email"
                              name="email"
                              value="<?php echo $email; ?>"
                              class="form-control"
                              placeholder="john.doe@example.com"
                              required
                              readonly />
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="profilePhoto">Profile Photo</label>
                          <div class="col-sm-10">
                            <input
                              type="file"
                              id="profilePhoto"
                              name="profilePhoto"
                              class="form-control"
                              accept="image/*" />
                          </div>
                        </div>
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                          </div>
                        </div>
                      </form>
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
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
  </body>
</html>