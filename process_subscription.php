<?php
include './config/config.php'; // Include your database connection

// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Retrieve posted data
$email = $_POST['email'];
$amount = $_POST['amount'];
$paymentReference = $_POST['paymentReference'];

// Ensure that intern_id and duration are available in the session
if (!isset($_SESSION['intern_id']) || !isset($_SESSION['siwes_duration'])) {
    echo json_encode(['success' => false, 'message' => 'Session data is missing.']);
    exit();
}

$internId = $_SESSION['intern_id'];
$siwesDuration = $_SESSION['siwes_duration']; // Duration in months

// Insert subscription into the database
$sql = "INSERT INTO intern_subscriptions (intern_id, email, amount, payment_reference, status, created_at, expiration_date) 
        VALUES (?, ?, ?, ?, 'paid', NOW(), DATE_ADD(NOW(), INTERVAL ? MONTH))";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdsi", $internId, $email, $amount, $paymentReference, $siwesDuration);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Subscription processed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving subscription: ' . $stmt->error]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>