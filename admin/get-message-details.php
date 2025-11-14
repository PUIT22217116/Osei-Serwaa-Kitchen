<?php
/**
 * API endpoint to fetch details for a single contact message.
 * It also marks the message as 'read' upon fetching.
 */
require_once __DIR__ . '/init.php'; // Handles auth and core includes

header('Content-Type: application/json');

$message_id = $_GET['id'] ?? null;

if (!$message_id) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Message ID is required.']);
    exit;
}

$db = new Database();

// Mark the message as 'read' when it's fetched
$db->updateContactMessageStatus($message_id, 'read');

// Fetch the full message details
$message = $db->getContactMessageById($message_id);

if ($message) {
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    http_response_code(404); // Not Found
    echo json_encode(['success' => false, 'message' => 'Message not found.']);
}