<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
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

        // Attempt to send WhatsApp notification if enabled
        $wa_sent = false;
        if (defined('WA_ENABLE') && WA_ENABLE) {
            // Build the WhatsApp message
            $wa_message = "*New Reservation* 📅\n\n";
            $wa_message .= "*Name:* " . $data['name'] . "\n";
            $wa_message .= "*Email:* " . $data['email'] . "\n";
            $wa_message .= "*Phone:* " . $data['phone'] . "\n";
            $wa_message .= "*Guests:* " . $data['guests'] . "\n";
            $wa_message .= "*Date:* " . $data['date'] . "\n";
            $wa_message .= "*Time:* " . $data['time'] . "\n";
            if (!empty($data['occasion'])) $wa_message .= "*Occasion:* " . ucfirst($data['occasion']) . "\n";
            if (!empty($data['notes'])) $wa_message .= "\n*Notes:* " . $data['notes'];

            $wa_result = send_whatsapp_message(defined('WA_ADMIN_NUMBER') ? WA_ADMIN_NUMBER : '', $wa_message);
            $wa_sent = !empty($wa_result['success']);
            if (!$wa_sent) {
                error_log('WhatsApp reservation send failed: ' . ($wa_result['response'] ?? 'no-response'));
            }
        }
        echo json_encode(['success' => true, 'message' => 'Reservation created successfully.', 'wa_sent' => $wa_sent]);
    } else {
        throw new Exception('Failed to save reservation to the database.');
    }
} catch (Exception $e) {
    // In a real app, you would log the error message ($e->getMessage())
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again later.']);
}
?>