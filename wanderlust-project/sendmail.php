<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow CORS for local development
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Read and decode JSON input
$rawData = file_get_contents("php://input");
file_put_contents('debug.log', date('Y-m-d H:i:s') . " Raw Input: $rawData\n", FILE_APPEND);
$data = json_decode($rawData, true);

// Check if JSON decoding failed
if (!$data) {
    $error = ['error' => 'Invalid JSON input'];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Error: Invalid JSON\n", FILE_APPEND);
    echo json_encode($error);
    exit;
}

// Extract and sanitize data
$name = htmlspecialchars(trim($data['name'] ?? ''));
$email = htmlspecialchars(trim($data['email'] ?? ''));
$phone = htmlspecialchars(trim($data['phone'] ?? ''));
$subject = htmlspecialchars(trim($data['subject'] ?? ''));
$message = htmlspecialchars(trim($data['message'] ?? ''));

// Log sanitized data
file_put_contents('debug.log', date('Y-m-d H:i:s') . " Data: " . json_encode($data) . "\n", FILE_APPEND);

// Validate inputs
if (empty($name) || empty($email) || empty($message)) {
    $error = ['error' => 'Please fill in all required fields'];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Error: Missing required fields\n", FILE_APPEND);
    echo json_encode($error);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = ['error' => 'Please enter a valid email address'];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Error: Invalid email\n", FILE_APPEND);
    echo json_encode($error);
    exit;
}

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'wanderlust-project';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    $error = ['error' => 'Database connection failed: ' . $conn->connect_error];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Error: DB connection failed - " . $conn->connect_error . "\n", FILE_APPEND);
    echo json_encode($error);
    exit;
}

// Prepare and execute SQL
$stmt = $conn->prepare("INSERT INTO contact_form (name, email, phone, subject, message, submitted_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

if ($stmt->execute()) {
    $success = ['success' => 'Message received. We’ll contact you soon!'];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Success: Message saved\n", FILE_APPEND);
    echo json_encode($success);
} else {
    $error = ['error' => 'Failed to save message: ' . $stmt->error];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " Error: Failed to save - " . $stmt->error . "\n", FILE_APPEND);
    echo json_encode($error);
}

// Clean up
$stmt->close();
$conn->close();
?>