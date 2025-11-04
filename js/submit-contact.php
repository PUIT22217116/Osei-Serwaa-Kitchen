<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// --- Server-side Validation ---
$errors = [];
$data = [];

$data['name'] = trim($_POST['name'] ?? '');
if (empty($data['name'])) $errors['name'] = 'Full Name is required.';

$data['email'] = trim($_POST['email'] ?? '');
if (empty($data['email'])) {
    $errors['email'] = 'Email is required.';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

$data['subject'] = trim($_POST['subject'] ?? '');
if (empty($data['subject'])) $errors['subject'] = 'Please select a subject.';

$data['message'] = trim($_POST['message'] ?? '');
if (empty($data['message'])) $errors['message'] = 'Message is required.';

$data['phone'] = trim($_POST['phone'] ?? ''); // Optional

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => 'Please correct the errors below.', 'errors' => $errors]);
    exit;
}

// --- Save to Database ---
try {
    $db = new Database();
    if ($db->saveContactMessage($data)) {
        // Optionally, send a confirmation email here

        // Attempt to send WhatsApp notification (server-side) if enabled
        $wa_sent = false;
        if (defined('WA_ENABLE') && WA_ENABLE) {
            // Build the WhatsApp message
            $wa_message = "*New Contact Form Submission* 📩\n\n";
            $wa_message .= "*Name:* " . $data['name'] . "\n";
            $wa_message .= "*Email:* " . $data['email'] . "\n";
            if (!empty($data['phone'])) $wa_message .= "*Phone:* " . $data['phone'] . "\n";
            $wa_message .= "*Subject:* " . $data['subject'] . "\n\n";
            $wa_message .= "*Message:*\n" . $data['message'];

            // Truncate to a safe length
            if (function_exists('mb_substr')) {
                $wa_message = mb_substr($wa_message, 0, 4000);
            } else {
                $wa_message = substr($wa_message, 0, 4000);
            }

            $wa_result = send_whatsapp_message(defined('WA_ADMIN_NUMBER') ? WA_ADMIN_NUMBER : '', $wa_message);
            $wa_sent = !empty($wa_result['success']);
            if (!$wa_sent) {
                error_log('WhatsApp send failed: ' . ($wa_result['response'] ?? 'no-response'));
            }
        }

        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.', 'wa_sent' => $wa_sent]);
    } else {
        throw new Exception('Failed to save message to the database.');
    }
} catch (Exception $e) {
    // In a real app, you would log the error message ($e->getMessage())
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again later.']);
}
?>