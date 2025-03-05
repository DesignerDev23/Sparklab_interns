<?php
include './config/config.php'; // Include your database connection

$message = ''; // Variable to hold any messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($token) || empty($password)) {
        $message = 'Token and Password are required.';
    } else {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
        $sql = "UPDATE subscribers SET password = '$hashedPassword', reset_token = NULL WHERE reset_token = '$token'";
        if ($conn->query($sql)) {
            $message = 'Password updated successfully.';
            echo '<script>setTimeout(() => { window.location.href = "index"; }, 2000);</script>';
        } else {
            $message = 'Error updating password: ' . $conn->error;
        }
    }
}

// Retrieve the token from the URL
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die('Invalid token.');
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Reset Password | SparkLab</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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
            <div class="authentication-inner py-4">
                <!-- Reset Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="dashboard" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <!-- Logo SVG here -->
                                </span>
                                <span class="app-brand-text demo text-body text-capitalize fw-bold">Spark Lab Hub</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Reset Password 🔒</h4>
                        <p class="mb-4">Please enter your new password below.</p>
                        <form action="reset_password.php" method="POST">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your new password" />
                            </div>
                            <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
                        </form>
                        <div class="text-center mt-4">
                            <a href="login.php" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Reset Password -->
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <?php if ($message): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $message; ?>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        var toastLive = document.getElementById('liveToast');
        var toast = new bootstrap.Toast(toastLive);
        toast.show();
    </script>
    <?php endif; ?>

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>