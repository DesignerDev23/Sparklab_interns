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
$paymentType = $_POST['paymentType']; // 🔹 Ensure this is retrieved from the form

// Ensure that intern_id and duration are available in the session
if (!isset($_SESSION['intern_id']) || !isset($_SESSION['siwes_duration'])) {
    echo json_encode(['success' => false, 'message' => 'Session data is missing.']);
    exit();
}

$internId = $_SESSION['intern_id'];
$siwesDuration = $_SESSION['siwes_duration']; // Duration in months

// Set payment status based on payment type
$status = ($paymentType === 'one-time') ? 'Paid Full' : 'Paid First Installment';

// Insert subscription into the database
$sql = "INSERT INTO intern_subscriptions (intern_id, email, amount, payment_reference, payment_type, status, created_at, expiration_date) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? MONTH))";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdsssi", $internId, $email, $amount, $paymentReference, $paymentType, $status, $siwesDuration);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Subscription processed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving subscription: ' . $stmt->error]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
