<?php
include './config/config.php'; // Include your database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve intern data
$email = $_SESSION['email'];
$sql = "SELECT * FROM interns WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['full_name'];
    $internId = $row['id'];
    $profileComplete = $row['profile_complete']; // Check profile completeness
} else {
    die('Intern not found.');
}

// Handle profile completion form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $dob = $_POST['dob'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $expert = $_POST['expert'] ?? '';
    $department = $_POST['department'] ?? '';
    $course = $_POST['course'] ?? '';

    // Handle file uploads
    $requestForm = $_FILES['request_form'] ?? null;
    $profilePicture = $_FILES['profile_picture'] ?? null;

    // Prepare for file uploads
    $requestFormPath = 'uploads/' . basename($requestForm['name']);
    $profilePicturePath = 'uploads/' . basename($profilePicture['name']);

    // Move uploaded files
    move_uploaded_file($requestForm['tmp_name'], $requestFormPath);
    move_uploaded_file($profilePicture['tmp_name'], $profilePicturePath);

    // Update data in the database
    $sql = "UPDATE interns SET dob=?, duration=?, expert=?, department=?, course=?, request_form=?, profile_picture=?, profile_complete=1 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $dob, $duration, $expert, $department, $course, $requestFormPath, $profilePicturePath, $internId);

    if ($stmt->execute()) {
        echo '<script>
            alert("Profile updated successfully!");
            window.location.href = "dashboard.php";
        </script>';
        exit();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Complete Your Profile | Spark Lab Hub</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!-- Config -->
    <script src="assets/js/config.js"></script>

    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .form-container {
            flex: 2; /* Takes up twice the space compared to the image */
            margin-right: 20px; /* Spacing between form and image */
        }
        .image-container {
            flex: 1; /* Default space for the image */
            justify-content: center;
            align-content: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column; /* Stack on smaller screens */
            }
            .form-container {
                margin-right: 0; /* Remove right margin */
                margin-bottom: 20px; /* Add bottom margin for spacing */
            }
        }
    </style>
</head>

<body>
    <div class="container-xxl p-20">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="col-11">
                <div class="container">
                    <div class="form-container">
                        <div class="card p-20">
                            <div class="card-body">
                                <div class="app-brand justify-content-center">
                                    <a href="dashboard" class="app-brand-link gap-2">
                                        <span class="app-brand-text demo text-body text-capitalize fw-bold">Spark Lab Hub</span>
                                    </a>
                                </div>

                                <h4 class="mb-2">Complete Your Profile 🚀</h4>
                                <p class="mb-4">Please fill in the additional information below!</p>

                                <form action="complete_profile.php" method="post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="dob">Date of Birth</label>
                                        <div class="col-sm-8">
                                            <input type="date" id="dob" name="dob" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="duration">Duration of SIWES</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="duration" name="duration" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="expert">Interested Expert</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="expert" name="expert" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="department">Department</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="department" name="department" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="course">Course</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="course" name="course" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="request_form">Request Form</label>
                                        <div class="col-sm-8">
                                            <input type="file" id="request_form" name="request_form" class="form-control" required />
                                            <div class="form-text">Upload your request form (max 5MB)</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label" for="profile_picture">Profile Picture</label>
                                        <div class="col-sm-8">
                                            <input type="file" id="profile_picture" name="profile_picture" class="form-control" required />
                                            <div class="form-text">Upload your profile photo (max 5MB)</div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end mb-3">
                                        <div class="col-sm-8">
                                            <button type="submit" class="btn btn-primary">Submit Profile</button>
                                        </div>
                                    </div>
                                </form>

                                <p class="text-center">
                                    <span>Need help?</span>
                                    <a href="help.php">
                                        <span>Contact Support</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="image-container">
                        <img src="./assets/img/profile data.gif" alt="Illustration" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>