<?php
include './config/config.php'; // Include your database connection

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = 'Email and Password are required.';
        $messageType = 'danger';
    } else {
        $sql = "SELECT * FROM interns WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                header('Location: dashboard.php'); // Redirect to the intern dashboard
                exit();
            } else {
                $message = 'Incorrect password.';
                $messageType = 'danger';
            }
        } else {
            $message = 'User not found.';
            $messageType = 'danger';
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login - Intern | Spark Lab Hub</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
</head>

<body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="intern_dashboard.php" class="app-brand-link gap-2"> <!-- Updated link to intern dashboard -->
                  <span class="app-brand-logo demo">
                    <svg width="120" height="120" viewBox="0 0 120 120" version="1.0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <rect width="120%" height="120%" fill="#ffffff" />
                      <g>
                        <path style="opacity:0.966" fill="#f6ae40" d="M 61.5,-0.5 C 63.1667,-0.5 64.8333,-0.5 66.5,-0.5C 70.3852,1.42966 72.3852,4.59633 72.5,9C 70.7391,19.9725 68.4057,30.8058 65.5,41.5C 61.1471,30.6072 57.4804,19.4406 54.5,8C 55.2675,3.74411 57.6008,0.910778 61.5,-0.5 Z"/>
                        <path style="opacity:0.966" fill="#f4ad40" d="M 116.5,14.5 C 116.5,15.5 116.5,16.5 116.5,17.5C 115.947,20.5867 114.28,23.0867 111.5,25C 102.667,30.5 93.8333,36 85,41.5C 84.8333,41.1667 84.6667,40.8333 84.5,40.5C 90.0395,29.9203 95.7062,19.4203 101.5,9C 108.486,5.83773 113.486,7.67106 116.5,14.5 Z"/>
                        <path style="opacity:0.965" fill="#f6af40" d="M 19.5,20.5 C 23.514,20.56 26.6806,22.2267 29,25.5C 34.0838,34.0029 39.4171,42.3363 45,50.5C 45.6877,51.3317 45.521,51.9984 44.5,52.5C 34.1825,47.6758 24.1825,42.3425 14.5,36.5C 10.151,29.2158 11.8177,23.8824 19.5,20.5 Z"/>
                        <path style="opacity:0.969" fill="#f6ae40" d="M -0.5,82.5 C -0.5,80.5 -0.5,78.5 -0.5,76.5C 1.29818,72.7632 4.29818,70.7632 8.5,70.5C 19.6627,72.2991 30.6627,74.7991 41.5,78C 30.6466,81.9633 19.6466,85.4633 8.5,88.5C 4.24042,88.2153 1.24042,86.2153 -0.5,82.5 Z"/>
                        <path style="opacity:0.97" fill="#f7af41" d="M 43.5,105.5 C 44.2389,105.369 44.9056,105.536 45.5,106C 40.5542,115.391 35.7209,124.891 31,134.5C 27.1457,138.671 22.6457,139.504 17.5,137C 14.4278,133.268 13.7612,129.101 15.5,124.5C 24.553,117.649 33.8864,111.315 43.5,105.5 Z"/>
                        <path style="opacity:0.959" fill="#f8b041" d="M 68.5,148.5 C 66.5,148.5 64.5,148.5 62.5,148.5C 58.9749,146.799 57.3082,143.965 57.5,140C 58.9776,129.284 61.1443,118.784 64,108.5C 67.8571,118.91 71.0238,129.577 73.5,140.5C 73.3276,144.193 71.6609,146.859 68.5,148.5 Z"/>
                        <path style="opacity:0.969" fill="#f7af41" d="M 116.5,128.5 C 116.5,129.833 116.5,131.167 116.5,132.5C 113.394,137.527 109.061,139.027 103.5,137C 96.5498,128.269 90.2165,119.102 84.5,109.5C 84.8333,109.167 85.1667,108.833 85.5,108.5C 94.5,113.333 103.5,118.167 112.5,123C 114.469,124.5 115.802,126.333 116.5,128.5 Z"/>
                    </g>
                  </svg>
                  </span>
                  <span class="app-brand-text demo text-body text-capitalize  fw-bold">Spark Lab Hub</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome to Spark Lab Hub! 👋</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p>

              <form id="formAuthentication" class="mb-3" action="index.php" method="post"> <!-- Corrected action to current file -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="text"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            required
                            autofocus />
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label>
                            <a href="forgot_password.php">
                                <small>Forgot Password?</small>
                            </a>
                        </div>
                        <div class="input-group input-group-merge">
                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" 
                                required />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                    </div>
                </form>
              <p class="text-center">
                <span>New on our platform?</span>
                <a href="signup.php">
                  <span>Create an account</span>
                </a>
              </p>
              <?php if ($message): ?>
                  <div class="alert alert-<?php echo $messageType; ?>">
                      <?php echo $message; ?>
                  </div>
              <?php endif; ?>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>