<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// --- Server-side Validation ---
$errors = [];
$data = [];

// Name
$data['name'] = trim($_POST['name'] ?? '');
if (empty($data['name'])) {
    $errors['name'] = 'Full Name is required.';
}

// Email
$data['email'] = trim($_POST['email'] ?? '');
if (empty($data['email'])) {
    $errors['email'] = 'Email is required.';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

// Phone
$data['phone'] = trim($_POST['phone'] ?? '');
if (empty($data['phone'])) {
    $errors['phone'] = 'Phone number is required.';
}

// Guests
$data['guests'] = filter_var($_POST['guests'] ?? 0, FILTER_VALIDATE_INT);
if ($data['guests'] <= 0) {
    $errors['guests'] = 'Please select the number of guests.';
}

// Date & Time
$data['date'] = $_POST['date'] ?? '';
$data['time'] = $_POST['time'] ?? '';
if (empty($data['date'])) $errors['date'] = 'Date is required.';
if (empty($data['time'])) $errors['time'] = 'Time is required.';

// Optional fields
$data['occasion'] = $_POST['occasion'] ?? null;
$data['notes'] = $_POST['notes'] ?? null;

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => 'Please correct the errors below.', 'errors' => $errors]);
    exit;
}

// --- Save to Database ---
try {
    $db = new Database();
    if ($db->createReservation($data)) {
        // Optionally, send a confirmation email here
        echo json_encode(['success' => true, 'message' => 'Reservation created successfully.']);
    } else {
        throw new Exception('Failed to save reservation to the database.');
    }
} catch (Exception $e) {
    // In a real app, you would log the error message ($e->getMessage())
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again later.']);
}
?>