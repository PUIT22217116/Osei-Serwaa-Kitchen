<?php
/**
 * API endpoint: fetch unread contact messages and return as JSON
 * Used by notifications dropdown in navbar
 */
require_once __DIR__ . '/init.php';

header('Content-Type: application/json');

$db = new Database();

// Fetch unread contact messages (limit to 5 most recent)
$messages = $db->getUnreadContactMessages(5);

// Count total unread
$unread_count = $db->countUnreadMessages();

echo json_encode([
    'success' => true,
    'unread_count' => $unread_count,
    'messages' => $messages
]);
?>
